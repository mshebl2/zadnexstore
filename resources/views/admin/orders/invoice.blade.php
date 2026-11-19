<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>فاتورة الطلب {{ $order->order_number }} | متجر زاد نيكس</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'IBM Plex Sans Arabic', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            background-color: #f3f4f6;
            color: #1f2937;
            padding: 24px;
            font-size: 13px;
            line-height: 1.5;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .action-bar {
            max-width: 800px;
            margin: 0 auto 20px auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #ffffff;
            padding: 14px 20px;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            border: 1px solid #e5e7eb;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            border: none;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .btn-primary {
            background-color: #242424;
            color: #0B2E75;
        }
        .btn-primary:hover {
            background-color: #000000;
        }

        .btn-secondary {
            background-color: #f3f4f6;
            color: #4b5563;
        }
        .btn-secondary:hover {
            background-color: #e5e7eb;
        }

        .invoice-box {
            max-width: 800px;
            margin: 0 auto;
            background: #ffffff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
            border: 1px solid #e5e7eb;
            position: relative;
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #0B2E75;
            padding-bottom: 24px;
            margin-bottom: 28px;
        }

        .brand-info {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .brand-logo {
            height: 58px;
            width: auto;
            object-contain: fit;
        }

        .brand-title {
            font-size: 20px;
            font-weight: 800;
            color: #242424;
            letter-spacing: -0.5px;
        }

        .brand-subtitle {
            font-size: 11px;
            color: #78716c;
            margin-top: 2px;
        }

        .invoice-meta {
            text-align: left;
            direction: ltr;
        }

        .invoice-badge {
            display: inline-block;
            background: #F8FAFC;
            color: #071E50;
            border: 1px solid #0B2E75;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 800;
            margin-bottom: 8px;
            direction: rtl;
        }

        .invoice-number {
            font-size: 18px;
            font-weight: 800;
            color: #242424;
            font-family: monospace;
        }

        .invoice-date {
            font-size: 11px;
            color: #6b7280;
            margin-top: 2px;
        }

        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 28px;
        }

        .details-card {
            background-color: #fafaf9;
            border: 1px solid #e7e5e4;
            border-radius: 12px;
            padding: 16px;
        }

        .card-header {
            font-size: 12px;
            font-weight: 800;
            color: #0B2E75;
            border-bottom: 1px solid #e7e5e4;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }

        .data-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
            font-size: 12px;
        }

        .data-label {
            color: #78716c;
            font-weight: 500;
        }

        .data-val {
            color: #1c1917;
            font-weight: 700;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 28px;
        }

        .items-table th {
            background-color: #242424;
            color: #ffffff;
            font-weight: 700;
            text-align: right;
            padding: 12px 14px;
            font-size: 12px;
        }

        .items-table th:first-child {
            border-top-right-radius: 8px;
            border-bottom-right-radius: 8px;
        }

        .items-table th:last-child {
            border-top-left-radius: 8px;
            border-bottom-left-radius: 8px;
            text-align: left;
        }

        .items-table td {
            padding: 14px;
            border-bottom: 1px solid #f3f4f6;
            font-size: 12px;
            color: #242424;
        }

        .items-table td:last-child {
            text-align: left;
            font-weight: 700;
        }

        .items-table tr:hover td {
            background-color: #fafaf9;
        }

        .summary-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 24px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
        }

        .qr-section {
            display: flex;
            align-items: center;
            gap: 14px;
            background: #fafaf9;
            border: 1px solid #e7e5e4;
            padding: 14px 18px;
            border-radius: 12px;
            max-width: 320px;
        }

        .qr-placeholder {
            width: 64px;
            height: 64px;
            background: #242424;
            color: #0B2E75;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-weight: 900;
            font-size: 18px;
            flex-shrink: 0;
            letter-spacing: -1px;
        }

        .qr-info {
            font-size: 10px;
            color: #78716c;
            line-height: 1.4;
        }

        .totals-table {
            width: 300px;
            border-collapse: collapse;
        }

        .totals-table td {
            padding: 6px 0;
            font-size: 12px;
        }

        .totals-table tr.grand-total td {
            border-top: 2px solid #0B2E75;
            padding-top: 10px;
            font-size: 15px;
            font-weight: 800;
            color: #242424;
        }

        .grand-price {
            color: #0B2E75;
            font-size: 18px;
            font-weight: 900;
            text-align: left;
        }

        .invoice-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px dashed #d1d5db;
            text-align: center;
            font-size: 11px;
            color: #9ca3af;
            line-height: 1.6;
        }

        .footer-highlight {
            color: #242424;
            font-weight: 700;
        }

        @media print {
            body {
                background: #ffffff !important;
                padding: 0 !important;
            }
            .action-bar {
                display: none !important;
            }
            .invoice-box {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
                max-width: 100% !important;
                width: 100% !important;
            }
        }
    </style>
</head>
<body>

    <!-- Print / Close Actions (Hidden on Print) -->
    <div class="action-bar">
        <div>
            <strong style="color: #242424; font-size: 14px;">فاتورة الطلب الرسمية</strong>
            <span style="color: #6b7280; font-size: 12px; margin-right: 8px;">جاهزة للطباعة أو الحفظ كـ PDF</span>
        </div>
        <div style="display: flex; gap: 10px;">
            <button onclick="window.print()" class="btn btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                <span>طباعة الفاتورة الآن</span>
            </button>
            <button onclick="window.close()" class="btn btn-secondary">إغلاق</button>
        </div>
    </div>

    <!-- Printable Official Invoice Box -->
    <div class="invoice-box">
        
        <!-- Header -->
        <div class="invoice-header">
            <div class="brand-info">
                <img src="{{ asset('images/logo.png') }}" alt="زاد نيكس" class="brand-logo" onerror="this.style.display='none'">
                <div>
                    <h1 class="brand-title">متجر زاد نيكس</h1>
                    <p class="brand-subtitle">ZADNEX STORE &bull; للمواد الغذائية والتموينية والمنظفات</p>
                    <p class="brand-subtitle">المملكة العربية السعودية &bull; سجل تجاري معتمد</p>
                </div>
            </div>

            <div class="invoice-meta">
                <div class="invoice-badge">فاتورة مبيعات معتمدة</div>
                <div class="invoice-number">#{{ $order->order_number }}</div>
                <div class="invoice-date">تاريخ الإصدار: {{ $order->created_at->format('Y-m-d H:i') }}</div>
            </div>
        </div>

        <!-- Customer & Order Details Grid -->
        <div class="details-grid">
            <!-- Customer -->
            <div class="details-card">
                <div class="card-header">بيانات العميل والتوصيل</div>
                <div class="data-row">
                    <span class="data-label">اسم العميل:</span>
                    <span class="data-val">{{ $order->customer_name }}</span>
                </div>
                <div class="data-row">
                    <span class="data-label">رقم الجوال:</span>
                    <span class="data-val" dir="ltr">{{ $order->customer_phone }}</span>
                </div>
                <div class="data-row">
                    <span class="data-label">البريد الإلكتروني:</span>
                    <span class="data-val">{{ $order->customer_email ?: '—' }}</span>
                </div>
                <div class="data-row">
                    <span class="data-label">عنوان الشحن:</span>
                    <span class="data-val">{{ $order->city }} - {{ $order->district }} - {{ $order->street_address }}</span>
                </div>
                @if($order->building_info)
                <div class="data-row">
                    <span class="data-label">المبنى / تفاصيل:</span>
                    <span class="data-val">{{ $order->building_info }}</span>
                </div>
                @endif
            </div>

            <!-- Payment & Shipment -->
            <div class="details-card">
                <div class="card-header">بيانات الدفع والشحن</div>
                <div class="data-row">
                    <span class="data-label">طريقة الدفع:</span>
                    <span class="data-val">
                        @if($order->payment_method === 'cod')
                            الدفع عند الاستلام (COD)
                        @else
                            بطاقة بنكية / إلكترونية (Paymob)
                        @endif
                    </span>
                </div>
                <div class="data-row">
                    <span class="data-label">حالة السداد:</span>
                    <span class="data-val" style="color: {{ $order->payment_status === 'paid' ? '#059669' : '#d97706' }};">
                        @if($order->payment_status === 'paid')
                            ● تم الدفع بنجاح
                        @else
                            ● بانتظار السداد
                        @endif
                    </span>
                </div>
                <div class="data-row">
                    <span class="data-label">شركة الشحن:</span>
                    <span class="data-val">{{ $order->shipping_company ?: 'مابت (MapIt Express)' }}</span>
                </div>
                @if($order->shipping_tracking_number)
                <div class="data-row">
                    <span class="data-label">رقم بوليصة التتبع:</span>
                    <span class="data-val" style="color: #071E50; font-family: monospace;">{{ $order->shipping_tracking_number }}</span>
                </div>
                @endif
                <div class="data-row">
                    <span class="data-label">حالة الطلب:</span>
                    <span class="data-val">
                        @switch($order->status)
                            @case('pending') قيد المراجعة @break
                            @case('processing') قيد التجهيز @break
                            @case('shipped') تم الشحن @break
                            @case('delivered') تم التوصيل @break
                            @case('cancelled') ملغي @break
                            @default {{ $order->status }}
                        @endswitch
                    </span>
                </div>
            </div>
        </div>

        <!-- Products Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>المنتج والمواصفات</th>
                    <th style="text-align: center; width: 80px;">الكمية</th>
                    <th style="text-align: center; width: 120px;">سعر الوحدة</th>
                    <th style="text-align: left; width: 130px;">الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $index => $item)
                <tr>
                    <td style="color: #9ca3af; font-weight: 700;">{{ $index + 1 }}</td>
                    <td>
                        <strong style="color: #242424;">{{ $item->product_name }}</strong>
                        @if($item->variant_name)
                            <div style="font-size: 11px; color: #6b7280;">{{ $item->variant_name }}</div>
                        @endif
                        <div style="font-size: 10px; color: #9ca3af; font-family: monospace;">SKU: {{ $item->sku }}</div>
                    </td>
                    <td style="text-align: center; font-weight: 700;">{{ $item->quantity }}</td>
                    <td style="text-align: center; font-weight: 600;">{{ number_format($item->unit_price, 2) }} ر.س</td>
                    <td style="text-align: left; color: #242424;">{{ number_format($item->total_price, 2) }} ر.س</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals & Verification Summary -->
        <div class="summary-container">
            <!-- QR / Store stamp -->
            <div class="qr-section">
                <div class="qr-placeholder">
                    IN
                </div>
                <div class="qr-info">
                    <strong style="color: #242424; font-size: 11px; display: block; margin-bottom: 2px;">فاتورة نظامية موثقة</strong>
                    تم إصدار الفاتورة إلكترونياً من منصة متجر زاد نيكس. جميع الأسعار تشمل ضريبة القيمة المضافة 15%.
                </div>
            </div>

            <!-- Financial Totals -->
            <table class="totals-table">
                <tr>
                    <td style="color: #6b7280;">المجموع الفرعي:</td>
                    <td style="text-align: left; font-weight: 700; color: #242424;">{{ number_format($order->subtotal, 2) }} ر.س</td>
                </tr>
                @if($order->discount_amount > 0)
                <tr>
                    <td style="color: #059669;">خصم الكوبون ({{ $order->coupon_code }}):</td>
                    <td style="text-align: left; font-weight: 700; color: #059669;">- {{ number_format($order->discount_amount, 2) }} ر.س</td>
                </tr>
                @endif
                <tr>
                    <td style="color: #6b7280;">رسوم الشحن والتوصيل:</td>
                    <td style="text-align: left; font-weight: 700; color: #242424;">
                        @if($order->shipping_cost > 0)
                            {{ number_format($order->shipping_cost, 2) }} ر.س
                        @else
                            <span style="color: #059669;">مجاني</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="color: #6b7280;">ضريبة القيمة المضافة (15% شاملة):</td>
                    <td style="text-align: left; font-weight: 600; color: #4b5563;">
                        {{ number_format(($order->total * 15) / 115, 2) }} ر.س
                    </td>
                </tr>
                <tr class="grand-total">
                    <td>الإجمالي النهائي المستحق:</td>
                    <td class="grand-price">{{ number_format($order->total, 2) }} ر.س</td>
                </tr>
            </table>
        </div>

        <!-- Footer -->
        <div class="invoice-footer">
            <p class="footer-highlight">شكراً لتسوقكم من متجر زاد نيكس — نسعد دائماً بخدمتكم وتوفير أجود المنتجات الغذائية والتموينية</p>
            <p>لأي استفسار بخصوص هذه الفاتورة أو الشحنة، يُسعدنا تواصلكم عبر البريد: info@zadnexstore.com أو عبر المتجر: zadnexstore.com</p>
        </div>

    </div>

    <script>
        // Auto trigger print dialog on page load
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 400);
        });
    </script>
</body>
</html>

