<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 24px; border-radius: 8px 8px 0 0; }
        .header h1 { margin: 0; font-size: 1.5rem; }
        .content { background: #fff; border: 1px solid #eee; border-top: none; padding: 24px; border-radius: 0 0 8px 8px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f8f9fa; }
        .total { font-size: 1.2rem; font-weight: bold; color: #DC143C; }
        .footer { margin-top: 24px; font-size: 0.9rem; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1><i class="fas fa-power-off"></i> Gizmo SD</h1>
        <p style="margin: 8px 0 0;">Order #{{ $order->id }} Confirmed</p>
    </div>
    <div class="content">
        <p>Hi {{ $order->user->name }},</p>
        <p>Thank you for your order! Here are the details:</p>

        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>${{ number_format($item->price, 2) }}</td>
                    <td>${{ number_format($item->subtotal, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <p class="total">Total: ${{ number_format($order->total, 2) }}</p>

        @if($order->shipping_address || $order->phone)
        <p><strong>Shipping to:</strong><br>
        {{ $order->formatShippingAddress() }}</p>
        @endif

        <p>We'll process your order soon. You can track your order status in your account.</p>
    </div>
    <div class="footer">
        <p>— Gizmo SD Store</p>
    </div>
</body>
</html>
