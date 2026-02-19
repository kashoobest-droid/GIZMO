<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Approved</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 0; background: #f5f5f5; }
        .email-container { background: white; }
        .header { background: linear-gradient(135deg, #DC143C 0%, #8B0000 100%); color: white; padding: 24px; text-align: center; border-bottom: 4px solid #DC143C; }
        .header-logo img { height: 48px; width: 48px; }
        .content { padding: 24px; }
        .title { color: #DC143C; font-size: 20px; font-weight: 700; margin-bottom: 8px; }
        .muted { color: #666; font-size: 14px; }
        .cta-button { display: inline-block; background: #DC143C; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; font-weight: 600; }
        .footer { background: #f5f5f5; padding: 16px 24px; border-top: 1px solid #ddd; font-size: 12px; color: #666; text-align: center; }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="header-logo">
                <img src="https://res.cloudinary.com/dgrnbtgts/image/upload/v1771338287/gizmo_qsab1d.png" alt="Gizmo Store">
            </div>
            <h2 style="margin:8px 0 0;">GIZMO Store</h2>
        </div>

        <div class="content">
            <div class="title">Payment Approved — Order #{{ $order->id }}</div>
            <p class="muted">Hi {{ $order->user->name }},</p>
            <p>Good news — your payment for <strong>Order #{{ $order->id }}</strong> has been approved by our team.</p>
            <p>Order total: <strong>@currency($order->total)</strong></p>

            <p>We are now processing your order and will notify you when it ships.</p>

            <p>
                <a href="{{ config('app.url') }}/orders/{{ $order->id }}" class="cta-button">View Order</a>
            </p>

            <hr style="border:none;border-top:1px solid #eee;margin:20px 0;">
            <p class="muted">If you have questions, reply to this email or contact our support team.</p>
        </div>

        <div class="footer">
            <strong>GIZMO Store</strong> © 2026
        </div>
    </div>
</body>
</html>
