<!DOCTYPE html>
<html dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>{{ app()->getLocale() === 'ar' ? 'فاتورة' : 'Invoice' }} #{{ $order->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.5;
        }

        /* Arabic (RTL) adjustments */
        @if(app()->getLocale() === 'ar')
        html, body {
            direction: rtl;
            text-align: right;
            unicode-bidi: embed;
            font-family: Amiri, 'DejaVu Sans', sans-serif;
        }

        /* Make table headers and cells RTL-friendly */
        th { text-align: right; }
        td { text-align: right; }
        .qty-cell { text-align: center; }
        .price-cell { text-align: right; }
        @endif

        .container {
            width: 100%;
            padding: 15px;
        }

        .header {
            border-bottom: 3px solid #DC143C;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .logo-section {
            font-size: 20px;
            font-weight: bold;
            color: #DC143C;
        }

        .invoice-meta {
            font-size: 10px;
            color: #666;
            margin-top: 5px;
        }

        .addresses {
            margin-bottom: 15px;
        }

        .address-row {
            margin-bottom: 10px;
        }

        .address-label {
            font-weight: bold;
            color: #DC143C;
            font-size: 10px;
            text-transform: uppercase;
        }

        .address-value {
            font-size: 10px;
            line-height: 1.4;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        thead tr {
            background-color: #f5f5f5;
            border-top: 1px solid #DC143C;
            border-bottom: 1px solid #DC143C;
        }

        th {
            padding: 8px;
            text-align: left;
            font-weight: bold;
            color: #000;
            font-size: 10px;
        }

        td {
            padding: 7px;
            border-bottom: 1px solid #ddd;
            font-size: 10px;
        }

        .qty-cell {
            text-align: center;
        }

        .price-cell {
            text-align: right;
        }

        .summary {
            margin-top: 10px;
            margin-bottom: 15px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 1px solid #eee;
            font-size: 10px;
        }

        .summary-row.total {
            border-top: 1px solid #DC143C;
            border-bottom: 2px solid #DC143C;
            font-weight: bold;
            padding: 8px 0;
            background-color: #f9f9f9;
        }

        .payment-status {
            padding: 8px;
            background-color: #f0f0f0;
            border-left: 3px solid #666;
            margin: 10px 0;
            font-size: 10px;
        }

        .payment-status p {
            margin: 3px 0;
        }

        .footer {
            border-top: 1px solid #ddd;
            padding-top: 10px;
            text-align: center;
            font-size: 9px;
            color: #999;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo-section">GIZMO STORE</div>
            <div class="invoice-meta">
                <p>{{ app()->getLocale() === 'ar' ? 'رقم الفاتورة' : 'Invoice #' }}: {{ $order->id }}</p>
                <p>{{ app()->getLocale() === 'ar' ? 'التاريخ' : 'Date' }}: {{ $order->created_at->format('M d, Y') }}</p>
            </div>
        </div>

        <!-- Addresses -->
        <div class="addresses">
            <div class="address-row">
                <div class="address-label">{{ app()->getLocale() === 'ar' ? 'عنوان الفاتورة' : 'Bill To' }}</div>
                <div class="address-value">
                    <strong>{{ $order->user->name }}</strong><br/>
                    {{ $order->user->email }}<br/>
                    {{ $order->user->phone ?? '' }}
                </div>
            </div>
            <div class="address-row">
                <div class="address-label">{{ app()->getLocale() === 'ar' ? 'عنوان التسليم' : 'Ship To' }}</div>
                <div class="address-value">
                    {{ $order->shipping_address ?? 'N/A' }}
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <table>
            <thead>
                <tr>
                    <th>{{ app()->getLocale() === 'ar' ? 'المنتج' : 'Product' }}</th>
                    <th class="qty-cell">{{ app()->getLocale() === 'ar' ? 'الكمية' : 'Qty' }}</th>
                    <th class="price-cell">{{ app()->getLocale() === 'ar' ? 'السعر' : 'Price' }}</th>
                    <th class="price-cell">{{ app()->getLocale() === 'ar' ? 'المجموع' : 'Total' }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product_name ?? $item->product->name ?? 'Item' }}</td>
                        <td class="qty-cell">{{ $item->quantity }}</td>
                        <td class="price-cell">{{ session('currency', 'SDG') }} {{ number_format($item->price, 2) }}</td>
                        <td class="price-cell">{{ session('currency', 'SDG') }} {{ number_format($item->quantity * $item->price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary -->
        <div class="summary">
            <div class="summary-row">
                <span>{{ app()->getLocale() === 'ar' ? 'الإجمالي الجزئي' : 'Subtotal' }}:</span>
                <span>{{ session('currency', 'SDG') }} {{ number_format($order->total + ($order->discount ?? 0), 2) }}</span>
            </div>
            @if(isset($order->discount) && $order->discount > 0)
                <div class="summary-row">
                    <span>{{ app()->getLocale() === 'ar' ? 'الخصم' : 'Discount' }}:</span>
                    <span>- {{ session('currency', 'SDG') }} {{ number_format($order->discount, 2) }}</span>
                </div>
            @endif
            <div class="summary-row total">
                <span>{{ app()->getLocale() === 'ar' ? 'الإجمالي' : 'Total' }}:</span>
                <span>{{ session('currency', 'SDG') }} {{ number_format($order->total, 2) }}</span>
            </div>
        </div>

        <!-- Payment Status -->
        <div class="payment-status">
            <p><strong>{{ app()->getLocale() === 'ar' ? 'حالة الدفع' : 'Payment Status' }}:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_status ?? 'pending')) }}</p>
            @if(isset($order->payment_method) && $order->payment_method)
                <p><strong>{{ app()->getLocale() === 'ar' ? 'طريقة الدفع' : 'Payment Method' }}:</strong> {{ ucfirst($order->payment_method) }}</p>
            @endif
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>{{ app()->getLocale() === 'ar' ? 'شكراً لتسوقك معنا' : 'Thank you for your business' }}</p>
            <p style="margin-top: 5px;">GIZMO STORE - {{ app()->getLocale() === 'ar' ? 'متجر جيزمو' : 'E-Commerce' }}</p>
        </div>
    </div>
</body>
</html>
