<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Display local mock payment simulator.
     */
    public function mockView(Request $request, $orderNumber)
    {
        if (app()->isProduction() && !$this->paymentService->isMockMode()) {
            abort(403, 'Mock payment mode is disabled in production.');
        }

        $order = Order::where('order_number', $orderNumber)->firstOrFail();
        $transactionId = $request->query('transaction_id', 'MOCK_' . time());

        return view('pages.mock-payment', compact('order', 'transactionId'));
    }

    /**
     * Process mock payment approval or failure safely.
     */
    public function mockProcess(Request $request, $orderNumber)
    {
        if (app()->isProduction() && !$this->paymentService->isMockMode()) {
            abort(403, 'Mock payment mode is disabled in production.');
        }

        $order = Order::with('items')->where('order_number', $orderNumber)->firstOrFail();
        $action = $request->input('action', 'success');
        $transactionId = $request->input('transaction_id', 'MOCK_' . time());

        DB::transaction(function () use ($order, $action, $transactionId) {
            $payment = Payment::firstOrNew(['order_id' => $order->id, 'transaction_id' => $transactionId]);
            $payment->provider = 'mock';
            $payment->payment_method = 'card';
            $payment->amount = $order->total;
            $payment->currency = 'SAR';

            if ($action === 'success') {
                $payment->status = 'success';
                $payment->raw_response = ['mock_status' => 'approved', 'processed_at' => now()->toIso8601String()];
                $payment->save();

                $order->payment_status = 'paid';
                $order->status = 'processing';
                $order->save();

                \App\Services\AffiliateService::processCommission($order);
            } else {
                $payment->status = 'failed';
                $payment->raw_response = ['mock_status' => 'declined', 'processed_at' => now()->toIso8601String()];
                $payment->save();

                $order->payment_status = 'failed';
                $order->status = 'cancelled';
                $order->save();

                // Restore stock when payment fails
                foreach ($order->items as $item) {
                    if ($item->product_id) {
                        Product::where('id', $item->product_id)->increment('stock_quantity', $item->quantity);
                    }
                    if ($item->variant_id) {
                        ProductVariant::where('id', $item->variant_id)->increment('stock_quantity', $item->quantity);
                    }
                }
            }
        });

        if ($action === 'success') {
            session(['last_placed_order' => $order->order_number]);
            return redirect()->route('checkout.confirmation', $order->order_number);
        }

        return redirect()->route('checkout.index')->with('error', 'تم رفض المعاملة في المحاكي التجريبي وتمت إعادة المخزون');
    }

    /**
     * Paymob Server-to-Server Webhook / Callback handler.
     */
    public function paymobWebhook(Request $request)
    {
        // 0. Handle browser return redirect (GET request when customer finishes or cancels in Paymob checkout)
        if ($request->isMethod('get')) {
            $isSuccess = $request->query('success') === 'true';
            $merchantOrderId = $request->query('special_reference') 
                ?: $request->query('merchant_order_id')
                ?: $request->query('order');

            if ($merchantOrderId) {
                $order = Order::where('order_number', $merchantOrderId)
                    ->orWhere('id', $merchantOrderId)
                    ->first();

                if ($order && ($isSuccess || $order->payment_status === 'paid')) {
                    session(['last_placed_order' => $order->order_number]);
                    return redirect()->route('checkout.confirmation', $order->order_number);
                }
            }

            if ($isSuccess) {
                return redirect()->route('home')->with('success', 'تم استلام الدفعة بنجاح!');
            }

            return redirect()->route('cart.index')->with('info', 'تمت العودة إلى سلة المشتريات، يمكنك مراجعة طلبك واستكماله في أي وقت.');
        }

        $payload = $request->all();
        $receivedHmac = $request->query('hmac', $request->header('X-Paymob-HMAC'));

        Log::info('Paymob Webhook received', ['has_hmac' => !empty($receivedHmac)]);

        // 1. Signature Verification (Mandatory in live mode to prevent spoofed callbacks)
        if (!$this->paymentService->isMockMode()) {
            if (empty($receivedHmac) || !$this->paymentService->verifyHmac($payload, $receivedHmac)) {
                Log::warning('Paymob HMAC verification failed or signature missing');
                return response()->json(['error' => 'Invalid or missing HMAC Signature'], 401);
            }
        } elseif ($receivedHmac && !$this->paymentService->verifyHmac($payload, $receivedHmac)) {
            Log::warning('Paymob Mock HMAC verification failed');
            return response()->json(['error' => 'Invalid HMAC Signature'], 401);
        }

        $obj = $request->input('obj');
        if (!$obj) {
            return response()->json(['status' => 'ignored'], 200);
        }

        $isSuccess = (bool) data_get($obj, 'success', false);
        $merchantOrderId = data_get($obj, 'order.merchant_order_id') 
            ?: data_get($obj, 'special_reference') 
            ?: data_get($obj, 'intention.special_reference') 
            ?: data_get($obj, 'merchant_order_id');
        $transactionId = (string) data_get($obj, 'id');
        $idempotencyKey = 'PAYMOB_TXN_' . $transactionId;

        // 2. Duplicate Webhook Protection (Idempotency)
        $existingPayment = Payment::where('idempotency_key', $idempotencyKey)->first();
        if ($existingPayment && $existingPayment->status === 'success') {
            Log::info("Paymob duplicate webhook ignored: {$idempotencyKey}");
            return response()->json(['status' => 'already_processed'], 200);
        }

        $order = Order::with('items')->where('order_number', $merchantOrderId)->first();
        if (!$order) {
            Log::warning("Paymob webhook order not found: {$merchantOrderId}");
            return response()->json(['error' => 'Order not found'], 404);
        }

        DB::transaction(function () use ($order, $obj, $isSuccess, $transactionId, $idempotencyKey) {
            $payment = Payment::firstOrNew(['order_id' => $order->id, 'transaction_id' => $transactionId]);
            $payment->provider = 'paymob';
            $payment->amount = data_get($obj, 'amount_cents', 0) / 100;
            $payment->currency = data_get($obj, 'currency', 'SAR');
            $payment->idempotency_key = $idempotencyKey;
            $payment->raw_response = $obj;

            if ($isSuccess) {
                $payment->status = 'success';
                $payment->save();

                $order->payment_status = 'paid';
                $order->status = 'processing';
                $order->save();

                \App\Services\AffiliateService::processCommission($order);
            } else {
                $payment->status = 'failed';
                $payment->save();

                $order->payment_status = 'failed';
                $order->status = 'cancelled';
                $order->save();

                // Restore stock
                foreach ($order->items as $item) {
                    if ($item->product_id) {
                        Product::where('id', $item->product_id)->increment('stock_quantity', $item->quantity);
                    }
                    if ($item->variant_id) {
                        ProductVariant::where('id', $item->variant_id)->increment('stock_quantity', $item->quantity);
                    }
                }
            }
        });

        return response()->json(['status' => 'success'], 200);
    }

    /**
     * Tamara Success Callback
     */
    public function tamaraSuccess(Request $request, $orderNumber)
    {
        $order = Order::with('items')->where('order_number', $orderNumber)->firstOrFail();
        $tamaraOrderId = (string) ($request->query('orderId') ?: ('TAMARA_' . $orderNumber));

        app(\App\Services\TamaraService::class)->authoriseOrder($tamaraOrderId);

        DB::transaction(function () use ($order, $tamaraOrderId, $request) {
            $payment = Payment::firstOrNew(['order_id' => $order->id, 'transaction_id' => $tamaraOrderId]);
            $payment->provider = 'tamara';
            $payment->payment_method = 'tamara_instalments';
            $payment->amount = $order->total;
            $payment->currency = 'SAR';
            $payment->status = 'success';
            $payment->raw_response = $request->all();
            $payment->save();

            $order->payment_status = 'paid';
            $order->status = 'processing';
            $order->save();

            \App\Services\AffiliateService::processCommission($order);
        });

        session(['last_placed_order' => $order->order_number]);
        return redirect()->route('checkout.confirmation', $order->order_number)->with('success', 'تم تأكيد طلبك والدفع بنجاح عبر تمارا');
    }

    /**
     * Tamara Failure Callback
     */
    public function tamaraFailure(Request $request, $orderNumber)
    {
        $order = Order::with('items')->where('order_number', $orderNumber)->firstOrFail();
        
        DB::transaction(function () use ($order) {
            $order->payment_status = 'failed';
            $order->status = 'cancelled';
            $order->save();

            foreach ($order->items as $item) {
                if ($item->product_id) {
                    Product::where('id', $item->product_id)->increment('stock_quantity', $item->quantity);
                }
                if ($item->variant_id) {
                    ProductVariant::where('id', $item->variant_id)->increment('stock_quantity', $item->quantity);
                }
            }
        });

        return redirect()->route('checkout.index')->with('error', 'تم إلغاء عملية الدفع عبر تمارا أو تعذر إكمالها.');
    }

    /**
     * Tamara Cancel Callback
     */
    public function tamaraCancel(Request $request, $orderNumber)
    {
        return $this->tamaraFailure($request, $orderNumber);
    }

    /**
     * Tamara Webhook / IPN Notification
     */
    public function tamaraWebhook(Request $request)
    {
        Log::info('Tamara Webhook: ' . json_encode($request->all()));
        $orderRef = $request->input('order_reference_id');
        $eventType = $request->input('event_type');

        if ($orderRef && $eventType === 'order_approved') {
            $order = Order::where('order_number', $orderRef)->first();
            if ($order && $order->payment_status !== 'paid') {
                $order->payment_status = 'paid';
                $order->status = 'processing';
                $order->save();
            }
        }

        return response()->json(['status' => 'acknowledged'], 200);
    }

    /**
     * Tamara Mock Simulator View
     */
    public function tamaraMockView(Request $request, $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();
        return view('pages.tamara-mock', compact('order'));
    }
}

