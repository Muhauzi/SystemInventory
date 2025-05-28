<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MidtransService
{
    protected $serverKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->serverKey = config('midtrans.server_key');
        $this->baseUrl = 'https://api.sandbox.midtrans.com/v2/';
    }

    public function getTransactionStatus($orderId)
    {
        $response = Http::withBasicAuth($this->serverKey, '')
            ->get($this->baseUrl . $orderId . '/status');

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }
}
