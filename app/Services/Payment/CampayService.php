<?php

namespace App\Services\Payment;

use Illuminate\Support\Facades\Http;
use Exception;

class CampayService
{
    protected $token;

    public function __construct()
    {
        $this->getToken();
    }

    protected function getToken()
    {
        $response = Http::post(config('campay.token_url'), [
            'username' => config('campay.username'),
            'password' => config('campay.password'),
        ]);

        if ($response->successful()) {
            $this->token = $response->json()['token'];
            return $this->token;
        }

        throw new Exception('Could not get CamPay token');
    }

    public function initiatePayment($amount, $phoneNumber, $description = null)
    {
        $response = Http::withToken($this->token)
            ->post(config('campay.collect_url'), [
                'amount' => $amount,
                'from' => $phoneNumber,
                'description' => $description ?? 'Payment for order',
                'external_reference' => uniqid('CP_'),
                'webhook_url' => config('campay.webhook_url'),
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        throw new Exception('Payment initiation failed: ' . $response->body());
    }

    public function checkStatus($reference)
    {
        $response = Http::withToken($this->token)
            ->get(config('campay.status_url') . $reference);

        if ($response->successful()) {
            return $response->json();
        }

        throw new Exception('Status check failed');
    }
}
