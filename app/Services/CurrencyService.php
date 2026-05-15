<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class CurrencyService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.exchangerate.host';

    public function __construct()
    {
        $this->apiKey = config('services.rates.api_key');
    }

    // Supported currencies
    public function supported()
    {
        return ['NGN','USD','EUR','GBP','GHS','KES'];
    }

    // Get conversion rate from base to target
    public function rate($from, $to)
    {
        $from = strtoupper($from);
        $to = strtoupper($to);
        if ($from === $to) return 1.0;

        $cacheKey = "fx_{$from}_{$to}";
        return Cache::remember($cacheKey, now()->addHours(6), function() use ($from, $to) {
            $url = "$this->baseUrl/latest?base={$from}&symbols={$to}";
            $res = Http::get($url);
            if ($res->ok() && isset($res['rates'][$to])) {
                return (float) $res['rates'][$to];
            }
            // fallback to 1.0 on failure
            return 1.0;
        });
    }

    // Detect currency from country code (ISO2)
    // System now only supports NGN
    public function currencyForCountry($countryCode)
    {
        return 'NGN';
    }
}
