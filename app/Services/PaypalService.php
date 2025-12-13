<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class PaypalService
{
    private string $baseUrl;
    private ?string $clientId;
    private ?string $clientSecret;

    public function __construct()
    {
        $mode = config('services.paypal.mode', 'sandbox');
        $this->baseUrl = $mode === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';

        $this->clientId = config('services.paypal.client_id');
        $this->clientSecret = config('services.paypal.client_secret');
    }

    public function createOrder(float $amount, string $currency, string $returnUrl, string $cancelUrl, ?string $customId = null): array
    {
        $token = $this->accessToken();

        $payload = [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'amount' => [
                    'currency_code' => $currency,
                    'value' => number_format($amount, 2, '.', ''),
                ],
            ]],
            'application_context' => [
                'return_url' => $returnUrl,
                'cancel_url' => $cancelUrl,
                'shipping_preference' => 'NO_SHIPPING',
                'user_action' => 'PAY_NOW',
            ],
        ];

        if ($customId) {
            $payload['purchase_units'][0]['custom_id'] = $customId;
        }

        $response = Http::withToken($token)
            ->post($this->baseUrl.'/v2/checkout/orders', $payload);

        if (!$response->successful()) {
            Log::error('PayPal create order failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new RuntimeException('No pudimos iniciar el pago con PayPal.');
        }

        return $response->json();
    }

    public function captureOrder(string $orderId): array
    {
        $token = $this->accessToken();

        // PayPal requiere un JSON vacio {} en el body para capturar
        $response = Http::withToken($token)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->withBody('{}', 'application/json')
            ->post($this->baseUrl."/v2/checkout/orders/{$orderId}/capture");

        if (!$response->successful()) {
            Log::error('PayPal capture failed', [
                'orderId' => $orderId,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new RuntimeException('No pudimos confirmar el pago con PayPal.');
        }

        return $response->json();
    }

    private function accessToken(): string
    {
        if (!$this->clientId || !$this->clientSecret) {
            throw new RuntimeException('PayPal no esta configurado. Falta client_id o client_secret.');
        }

        $response = Http::asForm()
            ->withBasicAuth($this->clientId, $this->clientSecret)
            ->post($this->baseUrl.'/v1/oauth2/token', [
                'grant_type' => 'client_credentials',
            ]);

        if (!$response->successful()) {
            Log::error('PayPal token failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new RuntimeException('No pudimos autenticarnos con PayPal.');
        }

        return $response->json('access_token');
    }
}
