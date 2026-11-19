<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use App\Models\StoreSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentService
{
    protected string $mode;
    protected ?string $apiKey;
    protected ?string $secretKey;
    protected ?string $publicKey;
    protected ?string $integrationId;
    protected ?string $applePayIntegrationId;
    protected ?string $iframeId;
    protected ?string $hmacSecret;
    protected string $currency;

    public function __construct()
    {
        $this->mode = StoreSetting::get('paymob_mode', config('services.paymob.mode', env('PAYMOB_MODE', 'mock')));
        $this->apiKey = StoreSetting::get('paymob_api_key', config('services.paymob.api_key', env('PAYMOB_API_KEY')));
        $this->secretKey = StoreSetting::get('paymob_secret_key', config('services.paymob.secret_key', env('PAYMOB_SECRET_KEY')));
        $this->publicKey = StoreSetting::get('paymob_public_key', config('services.paymob.public_key', env('PAYMOB_PUBLIC_KEY')));
        $this->integrationId = StoreSetting::get('paymob_integration_id', config('services.paymob.integration_id', env('PAYMOB_INTEGRATION_ID')));
        $this->applePayIntegrationId = StoreSetting::get('paymob_apple_pay_integration_id', env('PAYMOB_APPLE_PAY_INTEGRATION_ID', '24340'));
        $this->iframeId = StoreSetting::get('paymob_iframe_id', config('services.paymob.iframe_id', env('PAYMOB_IFRAME_ID')));
        $this->hmacSecret = StoreSetting::get('paymob_hmac_secret', config('services.paymob.hmac_secret', env('PAYMOB_HMAC_SECRET')));
        $this->currency = StoreSetting::get('paymob_currency', 'SAR');
    }

    public function isMockMode(): bool
    {
        return $this->mode === 'mock' || (empty($this->apiKey) && empty($this->secretKey));
    }

    /**
     * Initiate payment for an order.
     */
    public function initiatePayment(Order $order): array
    {
        if ($this->isMockMode()) {
            return $this->initiateMockPayment($order);
        }

        return $this->initiateLivePaymobPayment($order);
    }

    /**
     * Initiate mock payment for safe local development.
     */
    protected function initiateMockPayment(Order $order): array
    {
        $transactionId = 'MOCK_TXN_' . strtoupper(Str::random(12));

        $payment = Payment::create([
            'order_id' => $order->id,
            'provider' => 'mock',
            'transaction_id' => $transactionId,
            'payment_method' => 'card',
            'amount' => $order->total,
            'currency' => $this->currency,
            'status' => 'pending',
            'idempotency_key' => 'IDEMP_' . $order->order_number . '_' . time(),
            'raw_response' => [
                'note' => 'Local development mock transaction for ZADNEX STORE',
                'mode' => 'mock',
                'created_at' => now()->toISOString(),
            ],
        ]);

        return [
            'success' => true,
            'mode' => 'mock',
            'redirect_url' => route('payment.mock.view', ['order_number' => $order->order_number, 'transaction_id' => $transactionId]),
        ];
    }

    /**
     * Initiate real Paymob payment via server-side API.
     */
    protected function initiateLivePaymobPayment(Order $order): array
    {
        // 1. Prefer Modern Paymob KSA Unified Checkout (Intention API)
        if (!empty($this->secretKey) && !empty($this->publicKey)) {
            return $this->initiateIntentionPayment($order);
        }

        // 2. Fallback to Classic Paymob KSA Flow
        return $this->initiateClassicPayment($order);
    }

    /**
     * Modern Paymob Unified Checkout (Intention API supporting Mada, Visa, MC, Apple Pay).
     */
    protected function initiateIntentionPayment(Order $order): array
    {
        try {
            $amountCents = (int) round($order->total * 100);
            $methods = array_filter([
                (int) ($this->integrationId ?: 24343),
                (int) ($this->applePayIntegrationId ?: 24340),
            ]);

            $nameParts = explode(' ', trim($order->customer_name ?: 'عميل زاد نيكس'), 2);
            $firstName = $nameParts[0] ?? 'عميل';
            $lastName = $nameParts[1] ?? 'زاد نيكس';

            $response = Http::withHeaders([
                'Authorization' => 'Token ' . $this->secretKey,
                'Content-Type' => 'application/json',
            ])->when(app()->isLocal(), function ($http) {
                return $http->withoutVerifying();
            })->post('https://ksa.paymob.com/v1/intention/', [
                'amount' => $amountCents,
                'currency' => $this->currency,
                'payment_methods' => array_values($methods),
                'special_reference' => $order->order_number,
                'billing_data' => [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'phone_number' => $order->customer_phone,
                    'email' => $order->customer_email ?: 'info@zadnexstore.com',
                    'street' => $order->street_address ?: 'Street',
                    'building' => $order->building_info ?: 'NA',
                    'city' => $order->city ?: 'Riyadh',
                    'country' => 'SA',
                ],
            ]);

            if (!$response->successful()) {
                Log::error('Paymob Intention Failed: ' . $response->body());
                return ['success' => false, 'message' => 'تعذر تجهيز بوابة الدفع Paymob: ' . $response->json('detail', 'خطأ في الاتصال')];
            }

            $data = $response->json();
            $clientSecret = $data['client_secret'] ?? null;
            $intentionId = $data['id'] ?? ('INT_' . time());

            if (!$clientSecret) {
                return ['success' => false, 'message' => 'تعذر استخراج مفتاح العميل من بوابة الدفع'];
            }

            Payment::create([
                'order_id' => $order->id,
                'provider' => 'paymob',
                'transaction_id' => (string) $intentionId,
                'payment_method' => 'card',
                'amount' => $order->total,
                'currency' => $this->currency,
                'status' => 'pending',
                'idempotency_key' => 'PAYMOB_' . $order->order_number . '_' . $intentionId,
                'raw_response' => $data,
            ]);

            $redirectUrl = "https://ksa.paymob.com/unifiedcheckout/?publicKey={$this->publicKey}&clientSecret={$clientSecret}";

            return [
                'success' => true,
                'mode' => 'live',
                'redirect_url' => $redirectUrl,
            ];
        } catch (\Exception $e) {
            Log::error('Paymob Intention Exception: ' . $e->getMessage());
            return ['success' => false, 'message' => 'حدث خطأ تقني في معالجة بوابة الدفع'];
        }
    }

    /**
     * Classic Paymob Flow (Authentication Token -> Order -> Payment Key).
     */
    protected function initiateClassicPayment(Order $order): array
    {
        try {
            // 1. Authentication Request (KSA Endpoint)
            $authRes = Http::post('https://ksa.paymob.com/api/auth/tokens', [
                'api_key' => $this->apiKey,
            ]);

            if (!$authRes->successful()) {
                Log::error('Paymob Classic Auth Failed: ' . $authRes->body());
                return ['success' => false, 'message' => 'تعذر الاتصال ببوابة الدفع Paymob'];
            }

            $token = $authRes->json('token');

            // 2. Order Registration
            $amountCents = (int) round($order->total * 100);
            $orderRes = Http::post('https://ksa.paymob.com/api/ecommerce/orders', [
                'auth_token' => $token,
                'delivery_needed' => false,
                'amount_cents' => $amountCents,
                'currency' => $this->currency,
                'merchant_order_id' => $order->order_number,
                'items' => [],
            ]);

            if (!$orderRes->successful()) {
                Log::error('Paymob Order Creation Failed: ' . $orderRes->body());
                return ['success' => false, 'message' => 'تعذر إنشاء أمر الدفع في Paymob'];
            }

            $paymobOrderId = $orderRes->json('id');

            // 3. Payment Key Request
            $keyRes = Http::post('https://ksa.paymob.com/api/acceptance/payment_keys', [
                'auth_token' => $token,
                'amount_cents' => $amountCents,
                'expiration' => 3600,
                'order_id' => $paymobOrderId,
                'billing_data' => [
                    'apartment' => $order->building_info ?? 'NA',
                    'email' => $order->customer_email ?? 'info@zadnexstore.com',
                    'floor' => 'NA',
                    'first_name' => $order->customer_name,
                    'street' => $order->street_address,
                    'building' => $order->building_info ?? 'NA',
                    'phone_number' => $order->customer_phone,
                    'shipping_method' => 'PKG',
                    'postal_code' => 'NA',
                    'city' => $order->city,
                    'country' => 'SA',
                    'last_name' => 'Customer',
                    'state' => 'NA',
                ],
                'currency' => $this->currency,
                'integration_id' => (int) $this->integrationId,
            ]);

            if (!$keyRes->successful()) {
                Log::error('Paymob Payment Key Failed: ' . $keyRes->body());
                return ['success' => false, 'message' => 'تعذر استخراج مفتاح الدفع'];
            }

            $paymentKey = $keyRes->json('token');

            Payment::create([
                'order_id' => $order->id,
                'provider' => 'paymob',
                'transaction_id' => (string) $paymobOrderId,
                'payment_method' => 'card',
                'amount' => $order->total,
                'currency' => $this->currency,
                'status' => 'pending',
                'idempotency_key' => 'PAYMOB_' . $order->order_number . '_' . $paymobOrderId,
                'raw_response' => ['paymob_order_id' => $paymobOrderId],
            ]);

            $iframeUrl = "https://ksa.paymob.com/api/acceptance/iframes/{$this->iframeId}?payment_token={$paymentKey}";

            return [
                'success' => true,
                'mode' => 'live',
                'redirect_url' => $iframeUrl,
            ];
        } catch (\Exception $e) {
            Log::error('Paymob Classic Exception: ' . $e->getMessage());
            return ['success' => false, 'message' => 'حدث خطأ تقني في معالجة بوابة الدفع'];
        }
    }

    /**
     * Verify Paymob HMAC Signature according to official algorithm.
     */
    public function verifyHmac(array $data, string $receivedHmac): bool
    {
        if (empty($this->hmacSecret)) {
            return false;
        }

        // Official Paymob webhook HMAC concatenation order
        $keys = [
            'amount_cents',
            'created_at',
            'currency',
            'error_occured',
            'has_parent_transaction',
            'id',
            'integration_id',
            'is_3d_secure',
            'is_auth',
            'is_capture',
            'is_refunded',
            'is_standalone_payment',
            'is_voided',
            'order.id',
            'owner',
            'pending',
            'source_data.pan',
            'source_data.sub_type',
            'source_data.type',
            'success',
        ];

        $concatenated = '';
        foreach ($keys as $k) {
            $val = data_get($data, $k);
            if (is_bool($val)) {
                $concatenated .= $val ? 'true' : 'false';
            } else {
                $concatenated .= (string) $val;
            }
        }

        $calculatedHmac = hash_hmac('sha512', $concatenated, $this->hmacSecret);

        return hash_equals($calculatedHmac, $receivedHmac);
    }
}
