<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use App\Models\StoreSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TamaraService
{
    protected string $mode;
    protected ?string $apiToken;
    protected ?string $notificationToken;
    protected ?string $publicKey;
    protected string $currency;

    public function __construct()
    {
        $this->mode = StoreSetting::get('tamara_mode', 'sandbox');
        $this->apiToken = StoreSetting::get('tamara_api_token');
        $this->notificationToken = StoreSetting::get('tamara_notification_token');
        $this->publicKey = StoreSetting::get('tamara_public_key');
        $this->currency = 'SAR';
    }

    public function isEnabled(): bool
    {
        return StoreSetting::get('tamara_enabled', '0') === '1';
    }

    public function isMockMode(): bool
    {
        return $this->mode === 'mock' || empty($this->apiToken);
    }

    protected function getBaseUrl(): string
    {
        return $this->mode === 'live' ? 'https://api.tamara.co' : 'https://api-sandbox.tamara.co';
    }

    /**
     * Initiate Tamara Checkout for an order.
     */
    public function initiatePayment(Order $order): array
    {
        if ($this->isMockMode()) {
            return $this->initiateMockPayment($order);
        }

        return $this->initiateLiveTamaraPayment($order);
    }

    /**
     * Initiate Mock Tamara Payment for testing without API keys.
     */
    protected function initiateMockPayment(Order $order): array
    {
        $tamaraOrderId = 'TAMARA_MOCK_' . strtoupper(Str::random(10));

        Payment::create([
            'order_id' => $order->id,
            'provider' => 'tamara',
            'transaction_id' => $tamaraOrderId,
            'payment_method' => 'tamara_instalments',
            'amount' => $order->total,
            'currency' => $this->currency,
            'status' => 'pending',
            'idempotency_key' => 'TAMARA_' . $order->order_number . '_' . $tamaraOrderId,
            'raw_response' => ['mode' => 'mock', 'instalments' => 4],
        ]);

        return [
            'success' => true,
            'mode' => 'mock',
            'redirect_url' => route('payment.tamara.mock', ['order_number' => $order->order_number]),
        ];
    }

    /**
     * Normalize Saudi phone numbers to E.164 (+9665xxxxxxxx) format for Tamara API.
     */
    protected function formatSaudiPhone(?string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', (string) $phone);
        if (str_starts_with($phone, '00966')) {
            $phone = substr($phone, 2);
        } elseif (str_starts_with($phone, '05')) {
            $phone = '966' . substr($phone, 1);
        } elseif (str_starts_with($phone, '5')) {
            $phone = '966' . $phone;
        } elseif (!str_starts_with($phone, '966')) {
            $phone = '966' . $phone;
        }
        return '+' . $phone;
    }

    /**
     * Initiate Live / Sandbox payment with Tamara API.
     */
    protected function initiateLiveTamaraPayment(Order $order): array
    {
        try {
            $nameParts = explode(' ', trim($order->customer_name ?: 'عميل زاد نيكس'), 2);
            $firstName = $nameParts[0] ?? 'عميل';
            $lastName = $nameParts[1] ?? 'زاد نيكس';
            $formattedPhone = $this->formatSaudiPhone($order->customer_phone);

            $items = [];
            foreach ($order->items as $item) {
                $unitPrice = (float) $item->unit_price;
                $totalPrice = (float) $item->total_price;
                $items[] = [
                    'reference_id' => (string) ($item->sku ?: 'PROD-' . $item->id),
                    'type' => 'Perfume',
                    'name' => (string) ($item->product_name ?: 'منتجات متجر زاد نيكس'),
                    'sku' => (string) ($item->sku ?: 'SKU-' . $item->id),
                    'quantity' => (int) $item->quantity,
                    'unit_price' => [
                        'amount' => $unitPrice,
                        'currency' => $this->currency,
                    ],
                    'discount_amount' => [
                        'amount' => 0.00,
                        'currency' => $this->currency,
                    ],
                    'tax_amount' => [
                        'amount' => 0.00,
                        'currency' => $this->currency,
                    ],
                    'total_amount' => [
                        'amount' => $totalPrice,
                        'currency' => $this->currency,
                    ],
                ];
            }

            $shippingCost = (float) ($order->shipping_cost ?? 0.00);

            $payload = [
                'order_reference_id' => (string) $order->order_number,
                'order_number' => (string) $order->order_number,
                'total_amount' => [
                    'amount' => (float) $order->total,
                    'currency' => $this->currency,
                ],
                'shipping_amount' => [
                    'amount' => $shippingCost,
                    'currency' => $this->currency,
                ],
                'tax_amount' => [
                    'amount' => 0.00,
                    'currency' => $this->currency,
                ],
                'description' => "طلب مواد غذائية وتموينية من متجر زاد نيكس #{$order->order_number}",
                'country_code' => 'SA',
                'payment_type' => 'PAY_BY_INSTALMENTS',
                'instalments' => 4,
                'locale' => 'ar_SA',
                'consumer' => [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'phone_number' => $formattedPhone,
                    'email' => $order->customer_email ?: 'info@zadnexstore.com',
                ],
                'shipping_address' => [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'line1' => $order->street_address ?: 'طريق الملك فهد',
                    'city' => $order->city ?: 'الرياض',
                    'country_code' => 'SA',
                    'phone_number' => $formattedPhone,
                ],
                'billing_address' => [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'line1' => $order->street_address ?: 'طريق الملك فهد',
                    'city' => $order->city ?: 'الرياض',
                    'country_code' => 'SA',
                    'phone_number' => $formattedPhone,
                ],
                'merchant_url' => [
                    'success' => route('payment.tamara.success', ['order_number' => $order->order_number]),
                    'failure' => route('payment.tamara.failure', ['order_number' => $order->order_number]),
                    'cancel' => route('payment.tamara.cancel', ['order_number' => $order->order_number]),
                    'notification' => route('webhook.tamara'),
                ],
                'items' => $items,
            ];

            $client = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiToken,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->timeout(20);

            if (app()->environment('local')) {
                $client = $client->withoutVerifying();
            }

            $response = $client->post($this->getBaseUrl() . '/checkout', $payload);

            if (!$response->successful()) {
                Log::error('Tamara Checkout Request Failed: ' . $response->body());
                return [
                    'success' => false,
                    'message' => 'تعذر بدء جلسة الدفع عبر تمارا: ' . ($response->json('message') ?? 'خطأ في الاتصال'),
                ];
            }

            $data = $response->json();
            $checkoutUrl = $data['checkout_url'] ?? null;
            $orderId = $data['order_id'] ?? null;

            if (!$checkoutUrl) {
                return ['success' => false, 'message' => 'لم يتم استلام رابط الدفع من تمارا'];
            }

            Payment::create([
                'order_id' => $order->id,
                'provider' => 'tamara',
                'transaction_id' => (string) ($orderId ?: $order->order_number),
                'payment_method' => 'tamara_instalments',
                'amount' => $order->total,
                'currency' => $this->currency,
                'status' => 'pending',
                'idempotency_key' => 'TAMARA_' . $order->order_number . '_' . ($orderId ?: Str::random(8)),
                'raw_response' => $data,
            ]);

            return [
                'success' => true,
                'redirect_url' => $checkoutUrl,
            ];
        } catch (\Throwable $e) {
            Log::error('Tamara Exception: ' . $e->getMessage());
            return ['success' => false, 'message' => 'حدث خطأ تقني في الاتصال ببوابة تمارا'];
        }
    }

    /**
     * Authorise Tamara Order after customer completes payment.
     */
    public function authoriseOrder(string $tamaraOrderId): bool
    {
        if ($this->isMockMode()) {
            return true;
        }

        try {
            $client = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiToken,
                'Content-Type' => 'application/json',
            ])->timeout(15);

            if (app()->environment('local')) {
                $client = $client->withoutVerifying();
            }

            $response = $client->post($this->getBaseUrl() . "/orders/{$tamaraOrderId}/authorise");

            return $response->successful();
        } catch (\Throwable $e) {
            Log::error('Tamara Authorise Exception: ' . $e->getMessage());
            return false;
        }
    }
}

