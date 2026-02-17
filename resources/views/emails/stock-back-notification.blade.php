<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $storeName }} - Product Back in Stock</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
        }
        .header {
            background: linear-gradient(135deg, #DC143C 0%, #8B0000 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-bottom: 4px solid #DC143C;
        }
        .header-logo {
            display: inline-block;
            margin-bottom: 15px;
        }
        .header-logo img {
            height: 50px;
            width: 50px;
        }
        .header h1 {
            margin: 10px 0 5px;
            font-size: 1.8rem;
            font-weight: bold;
            letter-spacing: -0.5px;
        }
        .header-subtitle {
            margin: 5px 0 0;
            font-size: 0.95rem;
            opacity: 0.95;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 15px;
            margin-bottom: 20px;
            color: #333;
        }
        .product-card {
            background: #f9f9f9;
            border-left: 4px solid #DC143C;
            padding: 20px;
            margin: 30px 0;
            border-radius: 4px;
        }
        .product-name {
            font-size: 20px;
            font-weight: bold;
            color: #1a1a1a;
            margin-bottom: 10px;
        }
        .product-price {
            font-size: 24px;
            color: #DC143C;
            font-weight: bold;
            margin: 10px 0;
        }
        .product-description {
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
        }
        .stock-status {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .cta-button {
            display: inline-block;
            background: #DC143C;
            color: white;
            padding: 14px 32px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            margin: 20px 0;
            transition: background 0.3s ease;
        }
        .cta-button:hover {
            background: #8B0000;
            text-decoration: none;
        }
        .button-wrapper {
            text-align: center;
            margin: 30px 0;
        }
        .divider {
            border: none;
            border-top: 1px solid #ddd;
            margin: 30px 0;
        }
        .footer {
            background-color: #f5f5f5;
            padding: 20px 30px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
            text-align: center;
        }
        .footer p {
            margin: 5px 0;
        }
        .social-links {
            margin-top: 15px;
            text-align: center;
        }
        .social-links a {
            display: inline-block;
            width: 36px;
            height: 36px;
            line-height: 36px;
            border-radius: 50%;
            background: #DC143C;
            color: white !important;
            margin: 0 5px;
            text-decoration: none !important;
            font-size: 14px;
            font-weight: bold;
        }
        .helpful-text {
            background: #e7f3ff;
            border-left: 4px solid #DC143C;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            font-size: 13px;
            color: #222;
        }
        .helpful-text strong {
            color: #DC143C;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="header-logo">
                <img src="https://res.cloudinary.com/dgrnbtgts/image/upload/v1771338287/gizmo_qsab1d.png" alt="Gizmo Store Logo">
            </div>
            <h1>{{ $storeName }}</h1>
            <p class="header-subtitle">🎉 Good News! Your Product is Back in Stock</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                <p>Hi there!</p>
                <p>We have <strong>exciting news</strong>! A product you wanted is now <strong>back in stock</strong>.</p>
            </div>

            <!-- Product Card -->
            <div class="product-card">
                <div class="product-name">{{ $product->name }}</div>
                <div class="product-description">{{ $product->description }}</div>
                <div class="product-price">@currency($product->price)</div>
                <div style="margin: 15px 0;">
                    <span class="stock-status">✓ In Stock Now</span>
                </div>
            </div>

            <div class="helpful-text">
                <strong>⚡ Limited Stock Available:</strong> With only {{ $product->quantity }} unit{{ $product->quantity !== 1 ? 's' : '' }} in stock, we recommend ordering soon to avoid missing out!
            </div>

            <!-- Call to Action Button -->
            <div class="button-wrapper">
                <a href="{{ $productUrl }}" class="cta-button">Shop Now</a>
            </div>

            <p style="text-align: center; color: #666; font-size: 13px;">
                Or copy and paste this link in your browser:<br>
                <a href="{{ $productUrl }}" style="color: #DC143C; text-decoration: none; word-break: break-all; font-weight: 500;">{{ $productUrl }}</a>
            </p>

            <hr class="divider">

            <p style="color: #666; font-size: 14px;">
                Thank you for your interest in {{ $storeName }}. We're committed to helping you find the perfect tech products at unbeatable prices!
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>{{ $storeName }}</strong> © 2026. All rights reserved.</p>
            <p>Your trusted source for premium tech gadgets</p>
            <p style="margin-top: 10px; font-size: 11px;">{{ config('app.url') }}</p>
            <div class="social-links">
                <a href="https://www.facebook.com/gizmosudan/?locale=ar_AR" target="_blank" title="Facebook" style="color: white; text-decoration: none;">f</a>
                <a href="https://x.com/gizmosudan" target="_blank" title="X (Twitter)" style="color: white; text-decoration: none;">𝕏</a>
                <a href="https://www.instagram.com/gizmosudan" target="_blank" title="Instagram" style="color: white; text-decoration: none;">📷</a>
                <a href="https://www.tiktok.com/@gizmosudan" target="_blank" title="TikTok" style="color: white; text-decoration: none;">♪</a>
                <a href="https://wa.me/249919001000" target="_blank" title="WhatsApp" style="color: white; text-decoration: none;">💬</a>
            </div>
            <p style="margin: 15px 0 0 0; font-size: 11px;">
                If you no longer wish to receive these notifications, you can unsubscribe by replying to this email.
            </p>
        </div>
    </div>
</body>
</html>
