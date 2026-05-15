<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Payout;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class PayoutService
{
    private $client;
    private $paystackSecretKey;

    public function __construct()
    {
        $this->paystackSecretKey = config('services.paystack.secret');
        $this->client = new Client();
    }

    /**
     * Process instant payout to organizer's bank account
     * Called when a ticket payment is confirmed
     */
    public function processInstantPayout(Payment $payment)
    {
        try {
            // Get ticket and event details
            $ticket = $payment->ticket;
            $event = $ticket->event;
            $organizer = $event->user;

            // Calculate 95% payout (5% platform fee)
            $payoutAmount = $payment->amount * 0.95;

            // Validate organizer has bank details
            if (!$this->validateBankDetails($organizer)) {
                Log::warning("Payout skipped: Organizer missing bank details", [
                    'organizer_id' => $organizer->id,
                    'payment_id' => $payment->id,
                ]);
                return null;
            }

            // Create payout record
            $payout = Payout::create([
                'user_id' => $organizer->id,
                'payment_id' => $payment->id,
                'event_id' => $event->id,
                'amount' => $payoutAmount,
                'platform_fee' => $payment->amount * 0.05,
                'status' => 'pending',
                'reference' => 'PAYOUT-' . uniqid(),
            ]);

            // Attempt to transfer via Paystack
            $transfer = $this->transferToBank(
                $organizer,
                $payoutAmount,
                $payout->reference
            );

            if ($transfer['status']) {
                $payout->update([
                    'status' => 'completed',
                    'paystack_transfer_code' => $transfer['transfer_code'] ?? null,
                    'transferred_at' => now(),
                ]);

                Log::info("Payout successful", [
                    'payout_id' => $payout->id,
                    'amount' => $payoutAmount,
                    'organizer_id' => $organizer->id,
                ]);

                return $payout;
            } else {
                $payout->update([
                    'status' => 'retrying',
                    'error_message' => $transfer['message'] ?? 'Transfer failed',
                ]);

                Log::warning("Payout transfer failed, will retry", [
                    'payout_id' => $payout->id,
                    'error' => $transfer['message'] ?? 'Unknown error',
                ]);

                return $payout;
            }
        } catch (\Throwable $e) {
            Log::error("Payout processing error", [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return null;
        }
    }

    /**
     * Transfer money to organizer's bank account via Paystack
     */
    private function transferToBank($organizer, $amount, $reference)
    {
        try {
            // Step 1: Verify recipient and create transfer recipient if needed
            $recipientCode = $this->getOrCreateRecipient($organizer);

            if (!$recipientCode) {
                return [
                    'status' => false,
                    'message' => 'Failed to create or verify recipient',
                ];
            }

            // Step 2: Initiate transfer
            $response = $this->client->post('https://api.paystack.co/transfer', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->paystackSecretKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'source' => 'balance',
                    'reason' => 'Event ticket sales payout',
                    'amount' => (int)($amount * 100), // Convert to kobo
                    'recipient' => $recipientCode,
                    'reference' => $reference,
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            if ($data['status'] === true) {
                return [
                    'status' => true,
                    'transfer_code' => $data['data']['transfer_code'] ?? null,
                    'message' => 'Transfer initiated successfully',
                ];
            } else {
                return [
                    'status' => false,
                    'message' => $data['message'] ?? 'Transfer failed',
                ];
            }
        } catch (\Throwable $e) {
            Log::error("Bank transfer error", [
                'organizer_id' => $organizer->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'status' => false,
                'message' => 'Transfer API error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get or create Paystack recipient for organizer
     */
    private function getOrCreateRecipient($organizer)
    {
        try {
            // Check if organizer already has recipient code
            if ($organizer->paystack_recipient_code) {
                return $organizer->paystack_recipient_code;
            }

            // Create new recipient
            $response = $this->client->post('https://api.paystack.co/transferrecipient', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->paystackSecretKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'type' => 'nuban',
                    'name' => $organizer->account_name,
                    'account_number' => $organizer->account_number,
                    'bank_code' => $organizer->bank_code,
                    'currency' => 'NGN',
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            if ($data['status'] === true) {
                $recipientCode = $data['data']['recipient_code'];

                // Save recipient code for future use
                $organizer->update([
                    'paystack_recipient_code' => $recipientCode,
                ]);

                return $recipientCode;
            } else {
                Log::warning("Failed to create Paystack recipient", [
                    'organizer_id' => $organizer->id,
                    'message' => $data['message'] ?? 'Unknown error',
                ]);

                return null;
            }
        } catch (\Throwable $e) {
            Log::error("Recipient creation error", [
                'organizer_id' => $organizer->id,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Validate organizer has required bank details
     */
    private function validateBankDetails($organizer)
    {
        return !empty($organizer->account_number) &&
               !empty($organizer->account_name) &&
               !empty($organizer->bank_name) &&
               !empty($organizer->bank_code);
    }

    /**
     * Retry failed payouts
     */
    public function retryFailedPayouts()
    {
        $failedPayouts = Payout::where('status', 'retrying')
            ->where('updated_at', '<', now()->subHours(1))
            ->limit(10)
            ->get();

        foreach ($failedPayouts as $payout) {
            $organizer = $payout->user;

            $transfer = $this->transferToBank(
                $organizer,
                $payout->amount,
                $payout->reference
            );

            if ($transfer['status']) {
                $payout->update([
                    'status' => 'completed',
                    'paystack_transfer_code' => $transfer['transfer_code'] ?? null,
                    'transferred_at' => now(),
                ]);
            }
        }
    }

    /**
     * Get organizer's total earnings
     */
    public function getOrganizerEarnings($userId)
    {
        $totalRevenue = Payment::whereHas('ticket.event', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })
        ->where('status', 'completed')
        ->sum('amount');

        $paidOut = Payout::where('user_id', $userId)
            ->where('status', 'completed')
            ->sum('amount');

        return [
            'total_revenue' => $totalRevenue,
            'paid_out' => $paidOut,
            'pending' => ($totalRevenue * 0.95) - $paidOut,
            'platform_fees' => $totalRevenue * 0.05,
            'earnings' => $totalRevenue * 0.95,
        ];
    }
}
