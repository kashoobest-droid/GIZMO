<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Password Reset - GIZMO Store</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f5f5f5; margin:0; padding:0; }
        .wrapper { max-width:600px; margin:18px auto; background:#ffffff; border-radius:8px; overflow:hidden; }
        .header { background: linear-gradient(135deg,#DC143C 0%,#8B0000 100%); color:#fff; padding:20px; text-align:center; }
        .logo { height:48px; width:48px; display:inline-block; vertical-align:middle; }
        .brand { font-weight:700; font-size:18px; margin-left:8px; vertical-align:middle; }
        .content { padding:20px; color:#333; }
        .cta { display:inline-block; background:#DC143C; color:#fff; padding:12px 20px; border-radius:6px; text-decoration:none; font-weight:600; }
        .muted { color:#666; font-size:13px; }
        .footer { background:#f9f9f9; padding:14px 20px; text-align:center; font-size:12px; color:#888; }
        @media only screen and (max-width:480px) {
            .wrapper { margin:10px; }
            .content { padding:14px; }
            .header { padding:14px; }
            .brand { font-size:16px; }
            .cta { display:block; width:100%; text-align:center; }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <img src="https://res.cloudinary.com/dgrnbtgts/image/upload/v1771338287/gizmo_qsab1d.png" alt="Gizmo" class="logo">
            <span class="brand">GIZMO Store</span>
        </div>
        <div class="content">
            <p style="margin:0 0 12px;">Hi {{ $notifiable->name ?? 'Customer' }},</p>
            <p class="muted">We received a request to reset your password. Click the button below to choose a new password. This link will expire in {{ config('auth.passwords.'.config('auth.defaults.passwords').'.expire') }} minutes.</p>

            <div style="margin:18px 0; text-align:center;">
                <a href="{{ $url }}" class="cta">Reset Password</a>
            </div>

            <p class="muted">If you didn't request a password reset, you can safely ignore this email. If you have any concerns, contact our support.</p>
            <p style="margin-top:12px; font-size:13px; color:#333;">Thanks,<br>GIZMO Store Team</p>
        </div>
        <div class="footer">
            <div>{{ config('app.url') }}</div>
            <div style="margin-top:6px;">© {{ date('Y') }} GIZMO Store. All rights reserved.</div>
        </div>
    </div>
</body>
</html>
