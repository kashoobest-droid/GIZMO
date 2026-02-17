<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 0; background: #f5f5f5; }
        .email-container { background: white; }
        .header { background: linear-gradient(135deg, #DC143C 0%, #8B0000 100%); color: white; padding: 30px; text-align: center; border-bottom: 4px solid #DC143C; }
        .header-logo { display: inline-block; margin-bottom: 15px; }
        .header-logo img { height: 50px; width: 50px; }
        .header h1 { margin: 10px 0 5px; font-size: 1.8rem; font-weight: 700; letter-spacing: -0.5px; }
        .header p { margin: 5px 0 0; font-size: 0.95rem; opacity: 0.95; }
        .content { padding: 30px; }
        .greeting { font-size: 15px; margin-bottom: 20px; color: #333; }
        table { width: 100%; border-collapse: collapse; margin: 25px 0; background: #f9f9f9; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f0f0f0; font-weight: 600; color: #222; }
        td { font-size: 14px; }
        .total { font-size: 1.3rem; font-weight: bold; color: #DC143C; margin: 20px 0; padding: 15px; background: #fff3cd; border-left: 4px solid #DC143C; }
        .shipping-info { background: #f9f9f9; padding: 15px; border-left: 4px solid #DC143C; margin: 20px 0; font-size: 14px; }
        .shipping-info strong { color: #222; }
        .cta-button { display: inline-block; background: #DC143C; color: white; padding: 12px 28px; text-decoration: none; border-radius: 4px; font-weight: 600; margin: 20px 0; transition: background 0.3s; }
        .cta-button:hover { background: #8B0000; text-decoration: none; }
        .button-wrapper { text-align: center; margin: 30px 0; }
        .footer { background: #f5f5f5; padding: 20px 30px; border-top: 1px solid #ddd; font-size: 12px; color: #666; text-align: center; }
        .footer p { margin: 5px 0; }
        .social-links { margin-top: 15px; }
        .social-links a { display: inline-block; width: 36px; height: 36px; line-height: 36px; border-radius: 50%; background: #DC143C; color: white !important; margin: 0 5px; text-decoration: none !important; font-size: 14px; font-weight: bold; }
        .divider { border: none; border-top: 1px solid #ddd; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="header-logo">
                <img src="https://res.cloudinary.com/dgrnbtgts/image/upload/v1771338287/gizmo_qsab1d.png" alt="Gizmo Store Logo">
            </div>
            <h1>GIZMO Store</h1>
            <p>Order Confirmation</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                <p>Hi {{ $order->user->name }},</p>
                <p>Thank you for your order! We've received it and are processing it right away. Here are your order details:</p>
            </div>

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
                        <td>@currency($item->price)</td>
                        <td>@currency($item->subtotal)</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="total">
                Total Amount: @currency($order->total)
            </div>

            @if($order->shipping_address || $order->phone)
            <div class="shipping-info">
                <strong>📍 Shipping Information:</strong><br>
                {{ $order->formatShippingAddress() }}
                @if($order->phone)<br>Phone: {{ $order->phone }}@endif
            </div>
            @endif

            <p>Your order is being prepared and packed with care. You'll receive a shipping notification with tracking details as soon as your order leaves our warehouse.</p>

            <div class="button-wrapper">
                <a href="{{ config('app.url') }}/orders/{{ $order->id }}" class="cta-button">Track Your Order</a>
            </div>

            <hr class="divider">

            <p style="color: #666; font-size: 13px; margin: 20px 0;">
                If you have any questions about your order, please don't hesitate to contact us. We're here to help!
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>GIZMO Store</strong> © 2026. All rights reserved.</p>
            <p>Your trusted source for premium tech gadgets</p>
            <p style="margin-top: 10px; font-size: 11px;">{{ config('app.url') }}</p>
            <div class="social-links">
                <a href="https://www.facebook.com/gizmosudan/?locale=ar_AR" target="_blank" title="Facebook" style="color: white; text-decoration: none;">f</a>
                <a href="https://x.com/gizmosudan" target="_blank" title="X (Twitter)" style="color: white; text-decoration: none;">𝕏</a>
                <a href="https://www.instagram.com/gizmosudan" target="_blank" title="Instagram" style="color: white; text-decoration: none;">📷</a>
                <a href="https://www.tiktok.com/@gizmosudan" target="_blank" title="TikTok" style="color: white; text-decoration: none;">♪</a>
                <a href="https://wa.me/249919001000" target="_blank" title="WhatsApp" style="color: white; text-decoration: none;">💬</a>
            </div>
        </div>
    </div>
</body>
</html>
