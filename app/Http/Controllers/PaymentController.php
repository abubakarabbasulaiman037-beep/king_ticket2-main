<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Payment;
use App\Models\Ticket;
use App\Services\PayoutService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function showCheckout(Event $event)
    {
        return view('events.show', compact('event'));
    }

    public function initiate(Request $request, Event $event)
    {
        $buyer = Auth::user();
        $amount = (float) $event->price;

        // System only supports NGN (Nigerian Naira)
        $currency = 'NGN';
        $displayAmount = $amount;

        // Create payment record
        $payment = Payment::create([
            'event_id' => $event->id,
            'buyer_id' => $buyer->id,
            'amount' => $displayAmount,
            'currency' => $currency,
            'country' => 'NG',
            'reference' => 'pay_'.Str::random(12),
            'status' => 'initiated',
        ]);

        // Redirect to Paystack for payment processing
        $paystackKey = config('services.paystack.secret');
        $paystackInitUrl = 'https://api.paystack.co/transaction/initialize';

        // Ensure Paystack key is configured
        if (empty($paystackKey)) {
            $payment->update(['status' => 'failed']);
            return back()->with('error', 'Payment gateway not configured. Please contact support.');
        }

        // Currency is always NGN, no need to check

        $callbackUrl = route('payment.callback');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $paystackInitUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        $payload = [
            'email' => $buyer->email,
            'amount' => intval($displayAmount * 100),
            'reference' => $payment->reference,
            'callback_url' => $callbackUrl,
            'currency' => $currency,
        ];

        // Route payment directly to creator if subaccount code exists
        if (!empty($event->subaccount_code)) {
            $payload['subaccount'] = $event->subaccount_code;
        }

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer {$paystackKey}",
            'Content-Type: application/json',
        ]);

        $result = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            return back()->with('error', 'Payment initiation failed: '.$err);
        }

        $res = json_decode($result, true);
        if (!empty($res['data']['authorization_url'])) {
            return redirect($res['data']['authorization_url']);
        }

        return back()->with('error', 'Could not initiate payment.');
    }

    public function callback(Request $request)
    {
        $reference = $request->query('reference');
        if (!$reference) {
            return redirect()->route('events.public')->with('error', 'No reference supplied');
        }

        $payment = Payment::where('reference', $reference)->firstOrFail();

        $paystackKey = config('services.paystack.secret');
        if (empty($paystackKey)) {
            // Simulate success
            return $this->processSuccessfulPayment($reference);
        }

        $verifyUrl = "https://api.paystack.co/transaction/verify/{$reference}";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $verifyUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer {$paystackKey}"]);
        $result = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            return redirect()->route('events.public')->with('error', 'Verification failed: '.$err);
        }

        $res = json_decode($result, true);
        if (!empty($res['data']) && ($res['data']['status'] === 'success')) {
            return $this->processSuccessfulPayment($reference);
        }

        $payment->update(['status' => 'failed']);
        return redirect()->route('events.public')->with('error', 'Payment failed or not verified.');
    }

    protected function processSuccessfulPayment($reference)
    {
        $payment = Payment::where('reference', $reference)->firstOrFail();
        $payment->update(['status' => 'success']);

        // Create ticket for buyer and assign a sequential seat number for the event
        $event = Event::find($payment->event_id);

        // Prevent overselling
        if ($event->available_tickets <= 0) {
            $payment->update(['status' => 'failed']);
            return redirect()->route('events.public')->with('error', 'Tickets are sold out for this event.');
        }

        // Determine next seat number based on tickets already sold for the event
        $currentCount = $event->tickets()->count();
        $nextSeatNumber = $currentCount + 1;
        $seatLabel = 'Seat '.$nextSeatNumber; // format: Seat 1, Seat 2, ...

        $ticket = Ticket::create([
            'event_id' => $payment->event_id,
            'user_id' => $payment->buyer_id,
            'seat_number' => $seatLabel,
        ]);

        // Decrement available tickets
        $event->decrement('available_tickets');

        // Generate QR code image and save using external API to avoid package dependency
        $filename = 'ticket_'.$ticket->id.'_'.time().'.png';
        $path = storage_path('app/public/qrcodes/'.$filename);
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        $ticketUrl = route('tickets.show', $ticket->id);
        $qrApi = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data='.urlencode($ticketUrl);

        // Try using curl first
        $imageData = null;
        if (function_exists('curl_init')) {
            $ch = curl_init($qrApi);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $imageData = curl_exec($ch);
            curl_close($ch);
        }

        // Fallback to file_get_contents
        if (empty($imageData) && ini_get('allow_url_fopen')) {
            $imageData = @file_get_contents($qrApi);
        }

        if ($imageData) {
            file_put_contents($path, $imageData);
            $ticket->update(['qr_code' => $filename]);
        } else {
            // As a fallback, store a tiny placeholder
            $im = imagecreatetruecolor(300, 300);
            $bg = imagecolorallocate($im, 255, 255, 255);
            $text = imagecolorallocate($im, 0, 0, 0);
            imagefilledrectangle($im, 0, 0, 300, 300, $bg);
            imagestring($im, 5, 10, 140, 'QR GENERATION FAILED', $text);
            imagepng($im, $path);
            imagedestroy($im);
            $ticket->update(['qr_code' => $filename]);
        }

        // Generate PDF ticket (if the service is available)
        try {
            $pdfService = new \App\Services\PdfTicketService();
            $pdfUrl = $pdfService->generate($ticket);

            // Dispatch email job with ticket PDF
            dispatch(new \App\Jobs\SendTicketEmail($ticket->id));
        } catch (\Throwable $e) {
            // Log error but don't fail the payment
            \Log::error('Ticket generation failed after payment', [
                'ticket_id' => $ticket->id,
                'error' => $e->getMessage()
            ]);
            $pdfUrl = null;
        }

        // **INSTANT PAYOUT: Transfer 95% to organizer immediately**
        try {
            $payoutService = new PayoutService();
            $payout = $payoutService->processInstantPayout($payment);
            
            if ($payout && $payout->status === 'completed') {
                \Log::info('Instant payout completed: ' . $payout->id . ' - Amount: ' . $payout->amount);
            } elseif ($payout && $payout->status === 'pending') {
                \Log::info('Payout initiated (pending verification): ' . $payout->id);
            }
        } catch (\Exception $e) {
            \Log::error('Payout processing error: ' . $e->getMessage());
            // Payout failed but don't interrupt ticket delivery to buyer
        }

        return redirect()->route('tickets.show', $ticket->id)->with('success', 'Payment successful. Your ticket is ready.' . ($pdfUrl ? ' Download: ' . $pdfUrl : ''));
    }
}
