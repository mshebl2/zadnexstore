<?php

namespace App\Services;

use App\Models\Order;
use App\Models\StoreSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MapitService
{
    protected ?string $apiKey;
    protected string $baseUrl = 'https://backend.mapit.sa/api/v1';

    public function __construct()
    {
        $this->apiKey = StoreSetting::get('mapit_api_key', env('MAPIT_API_KEY', '1D9BCV1-1MEMN2R-PKXJTDY-PSHM2Q8'));
    }

    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    /**
     * Create order shipment in MapIt logistics platform.
     */
    public function createShipment(Order $order): array
    {
        if (!$this->isConfigured()) {
            return ['success' => false, 'message' => 'لم يتم إعداد مفتاح API الخاص بشركة مابت (MapIt) بعد.'];
        }

        try {
            // Clean phone number (extract Saudi mobile format without +966 or leading 0)
            $phone = preg_replace('/[^0-9]/', '', $order->customer_phone);
            if (str_starts_with($phone, '966')) {
                $phone = substr($phone, 3);
            } elseif (str_starts_with($phone, '0')) {
                $phone = substr($phone, 1);
            }

            $amountPayable = ($order->payment_method === 'cod' && $order->payment_status !== 'paid') 
                ? (float) $order->total 
                : 0.00;

            $payload = [
                'user' => [
                    'name' => $order->customer_name,
                    'phoneCode' => '+966',
                    'phone' => $phone ?: '500000000',
                    'address' => [
                        'city' => $order->city ?: 'الرياض',
                        'area' => $order->district ?: 'حي عام',
                        'street' => $order->street_address ?: 'العنوان الوطني',
                    ],
                ],
                'packagesCount' => max(1, (int) ($order->items->sum('quantity') ?: 1)),
                'weight' => max(1, (int) ceil($order->items->sum(function ($item) {
                    $raw = $item->product?->weight;
                    if (is_numeric($raw) && (float)$raw > 0) {
                        return (float)$raw * $item->quantity;
                    }
                    if (is_string($raw) && preg_match('/([\d\.]+)/', $raw, $m)) {
                        $num = (float)$m[1];
                        if (str_contains(strtolower($raw), 'g') && !str_contains(strtolower($raw), 'kg')) {
                            $num = max(0.1, $num / 1000);
                        }
                        return $num * $item->quantity;
                    }
                    return 0.5 * $item->quantity;
                }) ?: 1)),
                'merchantOrderNumber' => $order->order_number,
                'amountPayable' => $amountPayable,
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->when(app()->isLocal(), function ($http) {
                return $http->withoutVerifying();
            })->post("{$this->baseUrl}/order/integration", $payload);

            if (!$response->successful()) {
                Log::error('MapIt Shipment Creation Failed: ' . $response->body());
                $errMsg = $response->json('message') ?? 'فشل الاتصال بمنصة مابت';
                return ['success' => false, 'message' => 'خطأ من منصة مابت: ' . $errMsg];
            }

            $data = $response->json();
            $body = $data['body'] ?? [];
            $mapitOrderNumber = $body['orderNumber'] ?? null;
            $mapitId = $body['id'] ?? null;
            $status = $body['currentStatus'] ?? 'ORDER_PENDING';

            $trackingUrl = "https://www.mapit.sa/customer/track/{$mapitOrderNumber}";

            // Update order details
            $order->update([
                'shipping_company' => 'mapit',
                'shipping_tracking_number' => (string) $mapitOrderNumber,
                'shipping_label_url' => $trackingUrl,
                'shipping_status' => $status,
                'shipping_response' => $body,
                'status' => 'processing',
            ]);

            return [
                'success' => true,
                'order_number' => $mapitOrderNumber,
                'tracking_url' => $trackingUrl,
                'message' => 'تم إنشاء وإرسال الشحنة بنجاح عبر مابت (MapIt)',
            ];
        } catch (\Exception $e) {
            Log::error('MapIt Exception: ' . $e->getMessage());
            return ['success' => false, 'message' => 'حدث خطأ تقني أثناء الربط مع مابت: ' . $e->getMessage()];
        }
    }

    /**
     * Handle incoming webhook notifications from MapIt.
     */
    public function handleWebhook(array $payload): array
    {
        Log::info('MapIt Webhook Received', $payload);

        $merchantOrderNumber = $payload['merchantOrderNumber'] 
            ?? data_get($payload, 'order.merchantOrderNumber') 
            ?? data_get($payload, 'body.merchantOrderNumber');

        $mapitOrderNumber = $payload['orderNumber'] 
            ?? data_get($payload, 'order.orderNumber') 
            ?? data_get($payload, 'body.orderNumber');

        $status = $payload['status'] 
            ?? data_get($payload, 'order.status') 
            ?? data_get($payload, 'currentStatus') 
            ?? data_get($payload, 'body.currentStatus');

        $order = null;
        if ($merchantOrderNumber) {
            $order = Order::where('order_number', $merchantOrderNumber)->first();
        }
        if (!$order && $mapitOrderNumber) {
            $order = Order::where('shipping_tracking_number', (string) $mapitOrderNumber)->first();
        }

        if (!$order) {
            Log::warning('MapIt Webhook: Order not found', ['merchant' => $merchantOrderNumber, 'mapit' => $mapitOrderNumber]);
            return ['status' => 'not_found'];
        }

        $order->shipping_status = $status;
        if ($status === 'ORDER_COMPLETED' || $status === 'DELIVERED') {
            $order->status = 'delivered';
            if ($order->payment_method === 'cod') {
                $order->payment_status = 'paid';
            }
        } elseif (in_array($status, ['ORDER_PICKED_UP_FROM_MERCHANT', 'ORDER_IN_SHIPPING_COMPANY', 'ORDER_OUT_FOR_DELIVERY'])) {
            $order->status = 'shipped';
        }

        $order->save();

        return ['status' => 'success', 'order_id' => $order->id];
    }
}

