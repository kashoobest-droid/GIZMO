<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Order Is On The Way</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; color:#333; background:#f5f5f5; margin:0; }
        .container { max-width:600px; margin:20px auto; background:#fff; border-radius:6px; overflow:hidden; box-shadow:0 6px 18px rgba(0,0,0,0.06); }
        .header { background: linear-gradient(135deg, #DC143C 0%, #8B0000 100%); color:#fff; padding:18px; text-align:center; }
        .logo img{ height:46px; width:46px; }
        .content { padding:22px; }
        .title { font-size:20px; color:#222; font-weight:700; display:flex; gap:12px; align-items:center; }
        .truck { width:46px; height:46px; background:#fff; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; }
        .truck img{ width:28px; height:28px; }
        .body-text { margin-top:12px; color:#555; font-size:15px; }
        .cta { margin-top:18px; text-align:center; }
        .cta a { background:#DC143C; color:#fff; padding:10px 18px; border-radius:6px; text-decoration:none; font-weight:700; }
        .footer { padding:14px; background:#fafafa; text-align:center; font-size:12px; color:#777; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header" style="padding:28px 18px 12px;">
            <div style="text-align:center;">
                <img src="https://res.cloudinary.com/dgrnbtgts/image/upload/v1771507477/delivery_zsjccz.png" alt="truck" style="width:140px;height:140px;object-fit:contain;display:inline-block;background:#fff;padding:18px;border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,0.08);" />
            </div>
        </div>
        <div class="content">
            <div class="title" style="margin-top:6px;">Good news — your order is on the way!</div>

            <div class="body-text">
                Hi {{ $order->user->name }},<br>
                Your order <strong>#{{ $order->id }}</strong> has been shipped and is on its way to you. We hope you enjoy your purchase — tracking details are available in your account.
            </div>

            <div class="cta">
                <a href="{{ config('app.url') }}/orders/{{ $order->id }}">View Order & Tracking</a>
            </div>
        </div>
        <div class="footer">GIZMO Store — Thank you for shopping with us.</div>
    </div>
</body>
</html>
