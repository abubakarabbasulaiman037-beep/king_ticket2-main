<?php

namespace App\Services;

class PaystackService
{
    protected $secret;

    public function __construct()
    {
        $this->secret = config('services.paystack.secret');
    }

    protected function request($url, $data = null)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer {$this->secret}",
            'Content-Type: application/json',
        ]);
        if ($data !== null) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $resp = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        if ($err) {
            return ['status' => false, 'message' => $err];
        }
        return json_decode($resp, true);
    }

    public function resolveAccount($account_number, $bank_code)
    {
        $url = "https://api.paystack.co/bank/resolve?account_number={$account_number}&bank_code={$bank_code}";
        return $this->request($url);
    }

    public function createTransferRecipient($name, $account_number, $bank_code)
    {
        $url = 'https://api.paystack.co/transferrecipient';
        $data = [
            'type' => 'nuban',
            'name' => $name,
            'account_number' => $account_number,
            'bank_code' => $bank_code,
            'currency' => 'NGN',
        ];
        return $this->request($url, $data);
    }

    public function initiateTransfer($recipient_code, $amount, $reason = null)
    {
        $url = 'https://api.paystack.co/transfer';
        $data = [
            'source' => 'balance',
            'amount' => intval(round($amount * 100)),
            'recipient' => $recipient_code,
            'reason' => $reason,
        ];
        return $this->request($url, $data);
    }

    public function verifyTransfer($transfer_code)
    {
        $url = "https://api.paystack.co/transfer/verify/{$transfer_code}";
        return $this->request($url);
    }
}
