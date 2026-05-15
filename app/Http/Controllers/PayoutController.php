<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Payout;
use App\Services\PaystackService;
use Illuminate\Support\Facades\Auth;

class PayoutController extends Controller
{
    public function webhook(Request $request)
    {
        // Basic webhook receiver for Paystack transfer events
        $payload = $request->all();
        $event = $payload['event'] ?? null;

        if ($event === 'transfer.success' || $event === 'transfer.failed') {
            $data = $payload['data'] ?? [];
            $transfer_code = $data['transfer_code'] ?? null;
            $reference = $data['reference'] ?? null;

            if ($transfer_code) {
                $payout = Payout::where('transfer_code', $transfer_code)->first();
                if ($payout) {
                    $status = ($payload['event'] === 'transfer.success') ? 'success' : 'failed';
                    $payout->update(['status' => $status, 'meta' => $data]);
                }
            }
        }

        return response()->json(['received' => true]);
    }

    public function initiate(Event $event, PaystackService $paystack)
    {
        // Manual payout trigger for an event owner
        if (Auth::id() !== $event->user_id) {
            abort(403);
        }

        if (! $event->account_number || ! $event->bank_code) {
            return back()->with('error', 'Event does not have payout bank details.');
        }

        $balance = $event->payout_balance; // amount available for payout
        if ($balance <= 0) {
            return back()->with('error', 'No balance to payout.');
        }

        $fee = round($balance * 0.05, 2);
        $net = round($balance - $fee, 2);

        // create recipient
        $res = $paystack->createTransferRecipient($event->account_name ?? $event->user->name, $event->account_number, $event->bank_code);
        $recipient_code = $res['data']['recipient_code'] ?? null;

        $payout = Payout::create([
            'event_id' => $event->id,
            'amount' => $balance,
            'fee' => $fee,
            'net_amount' => $net,
            'recipient_code' => $recipient_code,
            'status' => 'initiated',
            'meta' => $res,
        ]);

        if ($recipient_code) {
            $transfer = $paystack->initiateTransfer($recipient_code, $net, 'Event payout');
            $transfer_code = $transfer['data']['transfer_code'] ?? null;
            $payout->update(['transfer_code' => $transfer_code, 'meta' => array_merge($payout->meta ?? [], ['transfer' => $transfer])]);
            // mark pending; webhook will update final status
        }

        // reset event payout balance to 0 (we assume auto transfer)
        $event->update(['payout_balance' => 0]);

        return back()->with('success', 'Payout initiated.');
    }
}
