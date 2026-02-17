<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="https://res.cloudinary.com/dgrnbtgts/image/upload/v1771338287/gizmo_qsab1d.png">
    <link rel="shortcut icon" href="https://res.cloudinary.com/dgrnbtgts/image/upload/v1771338287/gizmo_qsab1d.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <title>Gizmo Store - Your Tech Destination</title>
    <style>
        body {
            background-color: #f5f5f5;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        /* Navbar Styling */
        .navbar-custom {
            background-color: #1a1a1a;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .navbar-custom .navbar-brand {
            font-size: 1.8rem;
            font-weight: bold;
            color: #DC143C !important;
            letter-spacing: 1px;
        }

        .navbar-custom .nav-link {
            color: #ffffff !important;
            margin: 0 10px;
            transition: color 0.3s;
        }

        .navbar-custom .nav-link:hover {
            color: #DC143C !important;
        }

        /* Mobile responsive navbar */
        .navbar-toggler { border: 1px solid #ffffff !important; }
        .navbar-toggler:focus { box-shadow: 0 0 0 0.25rem rgba(220, 20, 60, 0.25) !important; }
        .navbar-toggler-icon { background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%23ffffff' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e") !important; }

        @media (max-width: 991px) {
            .navbar-custom .nav-link { margin: 0; padding: 0.75rem 0; border-bottom: 1px solid #3a3a3a; }
            .navbar-custom .nav-link:last-child { border-bottom: none; }
            html.dark-mode .navbar-custom .nav-link { border-bottom-color: #2a2a2a; }
            .search-container { margin: 15px 0 !important; width: 100%; }
            .search-container input { padding: 10px; border-radius: 5px; }
            .navbar-custom { padding: 0.5rem 0; }
            .navbar-nav { margin-left: 0 !important; margin-right: auto !important; }
            html[dir="rtl"] .navbar-nav { margin-left: auto !important; margin-right: 0 !important; }
        }

        /* User avatar in navbar dropdown */
        .nav-user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #DC143C;
            margin-right: 8px;
            vertical-align: middle;
        }

        .nav-user-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(220, 20, 60, 0.2);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 8px;
            font-size: 1.1rem;
            color: #DC143C;
        }

        .search-container {
            flex: 1;
            margin: 0 20px;
        }

        .search-container input {
            border-radius: 5px;
            padding: 8px 15px;
            width: 100%;
            border: none;
        }

        /* Header Section */
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 0;
            text-align: center;
        }

        .hero-section h1 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .hero-section p {
            font-size: 1.1rem;
            opacity: 0.95;
        }

        /* Category Filter Section */
        .category-section {
            background: white;
            padding: 20px 0;
            margin-bottom: 30px;
            border-bottom: 2px solid #ddd;
        }

        .category-btn {
            background: #f0f0f0;
            border: 1px solid #ddd;
            border-radius: 20px;
            padding: 8px 20px;
            margin: 5px;
            transition: all 0.3s;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
        }

        .category-btn:hover,
        .category-btn.active {
            background: #DC143C;
            color: white;
            border-color: #DC143C;
        }

        /* Product Card Styling */
        .product-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            transition: all 0.3s ease;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            transform: translateY(-5px);
        }

        .product-image-container {
            position: relative;
            overflow: hidden;
            background: #f9f9f9;
            height: 250px;
        }

        .product-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }

        .product-card:hover .product-image-container img {
            transform: scale(1.05);
        }

        .carousel-control-prev, .carousel-control-next {
            background: rgba(0,0,0,0.3);
            border-radius: 50%;
            width: 35px;
            height: 35px;
            top: 50%;
            transform: translateY(-50%);
        }

        .product-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #DC143C;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.85rem;
            font-weight: bold;
        }

        .product-body {
            padding: 15px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        html.dark-mode .product-body {
            color: #ffffff;
        }

        .product-name {
            font-size: 1.05rem;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 8px;
            line-height: 1.3;
            min-height: 2.6em;
        }

        html.dark-mode .product-name {
            color: #ffffff;
        }

        html.dark-mode .product-name a.text-dark {
            color: #ffffff !important;
        }

        .product-description {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 10px;
            flex-grow: 1;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        html.dark-mode .product-description {
            color: #d0d0d0;
        }

        .product-info {
            font-size: 0.9rem;
            color: #555;
            margin-bottom: 8px;
        }

        .product-price {
            font-size: 1.3rem;
            font-weight: bold;
            color: #DC143C;
            margin: 10px 0;
        }

        .category-badge {
            display: inline-block;
            background: #e8f4f8;
            color: #0066cc;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            margin-bottom: 10px;
        }

        .product-actions {
            display: flex;
            gap: 10px;
            margin-top: auto;
        }

        .btn-add-cart {
            flex: 1;
            background: #DC143C;
            border: none;
            color: white;
            padding: 10px;
            border-radius: 5px;
            font-weight: 600;
            transition: background 0.3s;
            cursor: pointer;
        }

        .btn-add-cart:hover {
            background: #8B0000;
            color: white;
            text-decoration: none;
        }

        .btn-wishlist {
            padding: 10px 15px;
            border: 1px solid #ddd;
            background: white;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-wishlist:hover {
            border-color: #DC143C;
            color: #DC143C;
        }

        .btn-wishlist.active {
            border-color: #DC143C;
            color: #DC143C;
        }

        .btn-wishlist.active i {
            color: #e74c3c;
        }

        .btn-in-cart {
            background: #28a745 !important;
        }

        .btn-in-cart:hover {
            background: #218838 !important;
            color: white !important;
        }

        .btn-warning {
            background-color: #DC143C;
        }

        .favorite-form { margin: 0; }

        /* No Products Message */
        .no-products {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .no-products i {
            font-size: 4rem;
            color: #ccc;
            margin-bottom: 20px;
        }

        /* Footer */
        .footer-custom {
            background: #1a1a1a;
            color: white;
            padding: 40px 0 20px;
            margin-top: 60px;
        }

        .footer-custom h6 {
            color: white;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .footer-custom p, .footer-custom li {
            color: rgba(255,255,255,0.85);
        }

        .footer-custom a {
            color: #DC143C;
            text-decoration: none;
        }

        .footer-custom a:hover {
            color: #FF8A8A;
            text-decoration: underline;
        }

        /* Social media icons - styled as before */
        .footer-custom .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(220, 20, 60, 0.2);
            color: #DC143C;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            text-decoration: none !important;
        }

        .footer-custom .social-links a:hover {
            background: #DC143C;
            color: #1a1a1a;
            transform: translateY(-3px);
            text-decoration: none !important;
        }

        .footer-custom .social-links a.me-3 {
            margin-right: 12px !important;
        }

        /* Floating Contact Button */
        .floating-contact-btn {
            position: fixed !important;
            bottom: 130px !important;
            right: 30px !important;
            width: 60px !important;
            height: 60px !important;
            background: linear-gradient(135deg, #DC143C 0%, #8B0000 100%) !important;
            border: none !important;
            border-radius: 50% !important;
            color: white !important;
            font-size: 24px !important;
            cursor: pointer !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            box-shadow: 0 4px 12px rgba(220, 20, 60, 0.4) !important;
            z-index: 9999 !important;
            transition: all 0.3s ease !important;
            text-decoration: none !important;
            visibility: visible !important;
            opacity: 1 !important;
        }
        
        .floating-contact-btn:hover {
            transform: scale(1.1) translateY(-5px) !important;
            box-shadow: 0 6px 20px rgba(220, 20, 60, 0.6) !important;
            color: white !important;
        }
        
        .floating-contact-btn:active {
            transform: scale(0.95) !important;
        }
        
        /* Dark mode floating button */
        html.dark-mode .floating-contact-btn {
            background: linear-gradient(135deg, #DC143C 0%, #8B0000 100%) !important;
        }
        
        /* RTL support */
        html[dir="rtl"] .floating-contact-btn {
            left: 30px !important;
            right: auto !important;
        }

        /* Offer card - modern */
        .offer-card-modern {
            margin-top: 12px;
            border-radius: 12px;
            overflow: hidden;
            background: linear-gradient(135deg, #fff8f0 0%, #fff0e6 50%, #ffe8d9 100%);
            border: 1px solid rgba(220, 20, 60, 0.25);
            box-shadow: 0 2px 12px rgba(220, 20, 60, 0.08);
        }
        .offer-card-modern__inner {
            display: flex;
            gap: 14px;
            align-items: center;
            padding: 12px 14px;
        }
        .offer-card-modern__media {
            position: relative;
            flex-shrink: 0;
            width: 72px;
            min-width: 72px;
            height: 72px;
            border-radius: 10px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }
        .offer-card-modern__img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }
        .offer-card-modern__badge {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: rgba(220, 20, 60, 0.95);
            color: #fff;
            padding: 2px 4px;
        }
        .offer-card-modern__body { flex: 1; min-width: 0; }
        .offer-card-modern__label {
            display: inline-block;
            font-size: 0.7rem;
            font-weight: 600;
            color: #c2410c;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }
        .offer-card-modern__title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0 0 4px 0;
            line-height: 1.25;
        }
        .offer-card-modern__desc {
            font-size: 0.8rem;
            color: #666;
            margin: 0 0 6px 0;
            line-height: 1.35;
        }
        .offer-card-modern__countdown {
            font-size: 0.75rem;
            color: #555;
        }
        .offer-card-modern__countdown-label { margin-right: 4px; }
        .offer-card-modern__countdown-value {
            font-weight: 700;
            color: #c2410c;
            font-variant-numeric: tabular-nums;
        }
        .offer-card-modern__meta { font-size: 0.75rem; color: #666; }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-section h1 {
                font-size: 1.8rem;
            }

            .search-container {
                margin: 15px 0;
            }

            .product-card {
                margin-bottom: 20px;
            }
        }

        /* RTL Support for Arabic */
        html[dir="rtl"] {
            direction: rtl;
            text-align: right;
        }

        html[dir="rtl"] .navbar-custom .navbar-brand {
            margin-left: auto;
            margin-right: 0;
        }

        html[dir="rtl"] .search-container {
            margin-left: 0;
            margin-right: 20px;
            direction: ltr;
        }

        html[dir="rtl"] .search-container .input-group {
            flex-direction: row-reverse;
        }

        html[dir="rtl"] .search-container input {
            text-align: right;
            border-radius: 0 5px 5px 0;
        }

        html[dir="rtl"] .search-container .btn-warning {
            border-radius: 5px 0 0 5px;
        }

        html[dir="rtl"] .navbar-collapse {
            text-align: right;
        }

        html[dir="rtl"] .ms-auto {
            margin-left: 0 !important;
            margin-right: auto !important;
        }

        html[dir="rtl"] .me-3 {
            margin-left: 12px !important;
            margin-right: 0 !important;
        }

        html[dir="rtl"] .floating-actions-container {
            right: auto;
            left: 30px;
        }

        html[dir="rtl"] .product-card {
            text-align: right;
        }

        html[dir="rtl"] .category-section {
            text-align: center;
        }

        html[dir="rtl"] .footer-custom {
            text-align: right;
        }

        /* Dark Mode Styles */
        :root {
            --color-bg: #f5f5f5;
            --color-bg-secondary: #ffffff;
            --color-text: #1a1a1a;
            --color-text-muted: #666666;
            --color-text-light: #555555;
            --color-border: #dddddd;
            --color-navbar-bg: #1a1a1a;
            --color-navbar-text: #ffffff;
            --color-navbar-hover: #DC143C;
            --color-hero-gradient-1: #667eea;
            --color-hero-gradient-2: #764ba2;
            --color-hero-text: #ffffff;
            --color-primary: #DC143C;
            --color-primary-hover: #8B0000;
            --color-success: #28a745;
            --color-success-hover: #218838;
            --color-footer-bg: #1a1a1a;
            --color-footer-text: rgba(255, 255, 255, 0.85);
            --color-footer-link: #DC143C;
            --color-card-bg: #ffffff;
            --color-card-shadow: rgba(0, 0, 0, 0.15);
            --color-category-bg: #f0f0f0;
            --color-offer-bg: #fff8f0;
            --color-offer-border: rgba(220, 20, 60, 0.25);
            --color-offer-shadow: rgba(220, 20, 60, 0.08);
            --color-offer-text: #1a1a1a;
        }

        html.dark-mode {
            --color-bg: #0f0f0f;
            --color-bg-secondary: #1a1a1a;
            --color-text: #ffffff;
            --color-text-muted: #cccccc;
            --color-text-light: #aaaaaa;
            --color-border: #333333;
            --color-navbar-bg: #0d0d0d;
            --color-navbar-text: #ffffff;
            --color-navbar-hover: #FF8A8A;
            --color-hero-gradient-1: #4a5a8a;
            --color-hero-gradient-2: #5a3a82;
            --color-hero-text: #ffffff;
            --color-primary: #FF8A8A;
            --color-primary-hover: #DC143C;
            --color-success: #26a745;
            --color-success-hover: #1e8e37;
            --color-footer-bg: #0d0d0d;
            --color-footer-text: rgba(255, 255, 255, 0.9);
            --color-footer-link: #FF8A8A;
            --color-card-bg: #1a1a1a;
            --color-card-shadow: rgba(0, 0, 0, 0.5);
            --color-category-bg: #2a2a2a;
            --color-offer-bg: #1f1510;
            --color-offer-border: rgba(220, 20, 60, 0.15);
            --color-offer-shadow: rgba(220, 20, 60, 0.15);
            --color-offer-text: #ffffff;
        }

        /* Apply dark mode colors to elements */
        body {
            background-color: var(--color-bg);
            color: var(--color-text);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .navbar-custom {
            background-color: var(--color-navbar-bg) !important;
        }

        .navbar-custom .navbar-brand {
            color: var(--color-primary) !important;
        }

        .navbar-custom .nav-link {
            color: var(--color-navbar-text) !important;
        }

        .navbar-custom .nav-link:hover {
            color: var(--color-navbar-hover) !important;
        }

        .hero-section {
            background: linear-gradient(135deg, var(--color-hero-gradient-1) 0%, var(--color-hero-gradient-2) 100%);
            color: var(--color-hero-text);
        }

        .hero-section h1,
        .hero-section p {
            color: var(--color-hero-text);
        }

        .category-section {
            background: var(--color-bg-secondary);
            border-bottom-color: var(--color-border);
            transition: background-color 0.3s ease;
        }

        .category-btn {
            background: var(--color-category-bg);
            border-color: var(--color-border);
            color: var(--color-text);
            transition: all 0.3s;
        }

        .category-btn:hover,
        .category-btn.active {
            background: var(--color-primary);
            color: white;
            border-color: var(--color-primary);
        }

        .product-card {
            background: var(--color-card-bg);
            border-color: var(--color-border);
            color: var(--color-text);
            transition: all 0.3s ease;
        }

        .product-card:hover {
            box-shadow: 0 10px 30px var(--color-card-shadow);
        }

        html.dark-mode .product-card {
            background: #242424;
            border-color: #373737;
        }

        .product-image-container {
            background: var(--color-bg);
        }

        .product-description {
            color: var(--color-text-muted);
            line-height: 1.5;
        }

        .product-info {
            color: var(--color-text-light);
        }

        html.dark-mode .product-description {
            color: #d0d0d0;
        }

        html.dark-mode .product-info {
            color: #b8b8b8;
        }

        .product-price {
            color: var(--color-primary);
        }

        .category-badge {
            background: var(--color-hero-gradient-1);
            color: white;
        }

        .btn-add-cart {
            background: var(--color-primary);
            border: none;
            color: white;
        }

        .btn-add-cart:hover {
            background: var(--color-primary-hover);
            color: white;
        }

        .btn-wishlist {
            border-color: var(--color-border);
            background: var(--color-card-bg);
            color: var(--color-text);
            transition: all 0.3s;
        }

        .btn-wishlist:hover {
            border-color: var(--color-primary);
            color: var(--color-primary);
        }

        .btn-wishlist.active {
            border-color: var(--color-primary);
            color: var(--color-primary);
        }

        .btn-in-cart {
            background: var(--color-success) !important;
        }

        .btn-in-cart:hover {
            background: var(--color-success-hover) !important;
        }

        .btn-warning {}

        .no-products {
            color: var(--color-text-muted);
        }

        .no-products i {
            color: var(--color-border);
        }

        .footer-custom {
            background: var(--color-footer-bg);
            color: var(--color-footer-text);
        }

        .footer-custom h6 {
            color: var(--color-navbar-text);
        }

        .footer-custom p,
        .footer-custom li {
            color: var(--color-footer-text);
        }

        .footer-custom a {
            color: var(--color-footer-link);
        }

        .footer-custom a:hover {
            color: var(--color-primary-hover);
        }

        .footer-custom .social-links a {
            background: rgba(220, 20, 60, 0.15);
            color: var(--color-primary);
            transition: all 0.3s ease;
        }

        .footer-custom .social-links a:hover {
            background: var(--color-primary);
            color: var(--color-navbar-bg);
        }

        .offer-card-modern {
            background: var(--color-offer-bg);
            border-color: var(--color-offer-border);
            box-shadow: 0 2px 12px var(--color-offer-shadow);
        }

        .offer-card-modern__title {
            color: var(--color-offer-text);
        }

        .offer-card-modern__desc {
            color: var(--color-text-muted);
        }

        .offer-card-modern__countdown {
            color: var(--color-text-light);
        }

        .offer-card-modern__meta {
            color: var(--color-text-muted);
        }

        /* Dark mode toggle button */
        #darkModeToggle {
            background: none;
            border: 1px solid var(--color-navbar-text);
            color: var(--color-navbar-text);
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        #darkModeToggle:hover {
            border-color: var(--color-navbar-hover);
            color: var(--color-navbar-hover);
        }

        /* Floating Action Buttons */
        .floating-actions-container {
            position: fixed;
            bottom: 30px;
            right: 30px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            z-index: 1000;
        }

        .floating-btn {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: none;
            background: white;
            color: #DC143C;
            font-size: 1.3rem;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .floating-btn:hover {
            background: #f0f0f0;
            transform: scale(1.15);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4);
        }

        .floating-btn:active {
            transform: scale(0.95);
        }

        html.dark-mode .floating-btn {
            background: white;
            color: #DC143C;
            box-shadow: 0 4px 12px rgba(220, 20, 60, 0.3);
        }

        html.dark-mode .floating-btn:hover {
            background: #f5f5f5;
            box-shadow: 0 6px 20px rgba(220, 20, 60, 0.4);
        }

        /* Language Switcher */
        .language-switcher-container {
            position: relative;
        }

        .language-dropdown {
            position: absolute;
            bottom: 75px;
            right: 0;
            background: var(--color-card-bg);
            border: 1px solid var(--color-border);
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            min-width: 150px;
            padding: 8px 0;
            animation: slideUp 0.3s ease;
        }

        html.dark-mode .language-dropdown {
            background: #2a2a2a;
            border-color: #444444;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .lang-option {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            color: var(--color-text);
            text-decoration: none;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }

        html.dark-mode .lang-option {
            color: #ffffff;
        }

        .lang-option:hover {
            background: var(--color-category-bg);
            color: var(--color-primary);
        }

        html.dark-mode .lang-option:hover {
            background: #3a3a3a;
            color: #FF8A8A;
        }        .lang-option.active {
            background: var(--color-hero-gradient-1);
            color: white;
            border-left-color: var(--color-primary);
            font-weight: 600;
        }

        .lang-flag {
            font-size: 1.2rem;
        }

        /* Responsive floating buttons */
        @media (max-width: 768px) {
            .floating-actions-container {
                bottom: 20px;
                right: 20px;
                gap: 12px;
            }

            .floating-btn {
                width: 55px;
                height: 55px;
                font-size: 1.1rem;
            }

            .language-dropdown {
                min-width: 140px;
                font-size: 0.9rem;
            }

            .lang-option {
                padding: 10px 14px;
            }
        }

        /* Bootstrap form elements dark mode */
        .form-control,
        .form-select {
            background-color: var(--color-card-bg);
            color: var(--color-text);
            border-color: var(--color-border);
            transition: all 0.3s ease;
        }

        html.dark-mode .form-control,
        html.dark-mode .form-select {
            background-color: #2a2a2a;
            color: #ffffff;
        }

        .form-control:focus,
        .form-select:focus {
            background-color: var(--color-card-bg);
            color: var(--color-text);
            border-color: var(--color-primary);
            box-shadow: 0 0 0 0.2rem rgba(220, 20, 60, 0.25);
        }

        .form-control::placeholder {
            color: var(--color-text-muted);
        }

        /* Dropdown menu dark mode */
        .dropdown-menu {
            background-color: var(--color-card-bg);
            border-color: var(--color-border);
        }

        .dropdown-item {
            color: var(--color-text);
        }

        .dropdown-item:hover,
        .dropdown-item:focus {
            background-color: var(--color-category-bg);
            color: var(--color-text);
        }

        .dropdown-divider {
            border-top-color: var(--color-border);
        }

        /* Alert boxes */
        .alert-success {
            background-color: var(--color-success);
            color: white;
            border-color: var(--color-success);
        }

        .alert-danger {
            background-color: #dc3545;
            color: white;
            border-color: #dc3545;
        }

        /* Search input dark mode */
        .search-container input {
            background-color: var(--color-card-bg);
            color: var(--color-text);
            border-color: var(--color-border);
        }

        html.dark-mode .search-container input {
            background-color: #2a2a2a;
            color: #ffffff;
        }

        .search-container input::placeholder {
            color: var(--color-text-muted);
        }

        html.dark-mode .search-container input::placeholder {
            color: #999999;
        }

        .search-container .btn-warning {
            border-radius: 5px;
            border: none;
            padding: 8px 15px;
            transition: all 0.3s ease;
            background: #DC143C !important;
            color: white !important;
        }

        .search-container .btn-warning:hover,
        .search-container .btn-warning:focus,
        .search-container .btn-warning:active {
            background: #8B0000 !important;
            color: white !important;
            box-shadow: 0 0 0 0.2rem rgba(220, 20, 60, 0.25) !important;
        }

        html.dark-mode .search-container .btn-warning {
            background: #DC143C !important;
            color: white !important;
        }

        html.dark-mode .search-container .btn-warning:hover,
        html.dark-mode .search-container .btn-warning:focus {
            background: #8B0000 !important;
            color: white !important;
        }

        html[dir="rtl"] .search-container .input-group .btn-warning {
            border-radius: 5px 0 0 5px;
            padding: 8px 15px;
        }

        /* Bootstrap text-muted dark mode */
        .text-muted {
            color: #6c757d;
        }

        html.dark-mode .text-muted {
            color: #d1d1d1;
        }    

        /* Newsletter text styling */
        .col-md-3 p.small {
            transition: color 0.3s ease;
        }

        html.dark-mode .col-md-3 p.small.text-muted {
            color: #d1d1d1 !important;
        }

        /* Badge styling */
        .badge {
            transition: all 0.3s ease;
        }

        /* Pagination dark mode */
        .pagination .page-link {
            background-color: var(--color-card-bg);
            border-color: var(--color-border);
            color: var(--color-text);
        }

        .pagination .page-link:hover {
            background-color: var(--color-category-bg);
            border-color: var(--color-primary);
            color: var(--color-primary);
        }

        .pagination .page-item.active .page-link {
            background-color: var(--color-primary);
            border-color: var(--color-primary);
            color: white;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                <i class="fas fa-power-off"></i> Gizmo Store
            </a>
            
            <div class="search-container d-none d-lg-block">
                <form action="{{ url('/') }}" method="GET">
                    <div class="input-group">
                        <input type="text" name="q" class="form-control" placeholder="{{ __('messages.nav_search') }}" value="{{ request('q') }}">
                        <button class="btn btn-warning" type="submit">{{ __('messages.nav_search_btn') }}</button>
                    </div>
                </form>
            </div>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <!-- Language Switcher -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="languageDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-globe"></i>
                            @if(app()->getLocale() === 'ar')
                                <span class="ms-1">العربية</span>
                            @else
                                <span class="ms-1">English</span>
                            @endif
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="languageDropdown">
                            <li>
                                <a class="dropdown-item {{ app()->getLocale() === 'en' ? 'active' : '' }}" href="{{ route('locale.set', 'en') }}">
                                    <span class="flag-icon">🇺🇸</span> English
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ app()->getLocale() === 'ar' ? 'active' : '' }}" href="{{ route('locale.set', 'ar') }}">
                                    <span class="flag-icon">🇸🇦</span> العربية
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/"><i class="fas fa-home"></i> {{ __('messages.nav_home') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/"><i class="fas fa-box"></i> {{ __('messages.nav_products') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="{{ auth()->check() ? route('cart.index') : route('login') }}">
                            <i class="fas fa-shopping-cart"></i> {{ __('messages.nav_cart') }}
                            @if(isset($cartCount) && $cartCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark">{{ $cartCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ auth()->check() ? route('favorites.index') : route('login') }}"><i class="fas fa-heart"></i> {{ __('messages.nav_favorites') }}</a>
                    </li>
                    @php use Illuminate\Support\Facades\Auth; @endphp
                    @if(Auth::check())
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                @if(Auth::user()->avatar)
                                    <img src="{{ filter_var(Auth::user()->avatar, FILTER_VALIDATE_URL) ? Auth::user()->avatar : asset(Auth::user()->avatar) }}" alt="Avatar" class="nav-user-avatar">
                                @else
                                    <span class="nav-user-icon"><i class="fas fa-user"></i></span>
                                @endif
                                <span>{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-user"></i> {{ __('messages.nav_profile') }}</a></li>
                                <li><a class="dropdown-item" href="{{ route('orders.index') }}"><i class="fas fa-box"></i> {{ __('messages.nav_orders') }}</a></li>
                                @if(Auth::user()->is_admin)
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="fas fa-chart-line"></i> {{ __('messages.nav_dashboard') }}</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.orders.index') }}"><i class="fas fa-box"></i> {{ __('messages.nav_manage_orders') }}</a></li>
                                    <li><a class="dropdown-item" href="{{ route('product.index') }}"><i class="fas fa-boxes"></i> {{ __('messages.nav_manage_products') }}</a></li>
                                    <li><a class="dropdown-item" href="{{ route('category.index') }}"><i class="fas fa-list"></i> {{ __('messages.nav_manage_categories') }}</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.coupons.index') }}"><i class="fas fa-tag"></i> {{ __('messages.nav_coupons') }}</a></li>
                                    <li><a class="dropdown-item" href="{{ route('users.index') }}"><i class="fas fa-users-cog"></i> {{ __('messages.nav_manage_users') }}</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="dropdown-item" style="border: none; background: none; cursor: pointer;">
                                            <i class="fas fa-sign-out-alt"></i> {{ __('messages.nav_logout') }}
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> {{ __('messages.nav_sign_in') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}"><i class="fas fa-user-plus"></i> {{ __('messages.nav_sign_up') }}</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <h1>{{ __('messages.hero_title') }}</h1>
            <p>{{ __('messages.hero_subtitle') }}</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container my-5">
        @include('partials.breadcrumbs', [
            'items' => array_filter([
                ['label' => 'Home', 'url' => url('/')],
                isset($categoryForBreadcrumb) ? ['label' => $categoryForBreadcrumb->name, 'url' => url('/?category=' . $categoryForBreadcrumb->id)] : null,
            ])
        ])
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        <!-- Category Filter & Sort -->
        <div class="category-section">
            <div class="d-flex justify-content-center flex-wrap align-items-center gap-2">
                <a href="{{ url('/') }}" class="category-btn {{ !request()->has('category') ? 'active' : '' }}">
                    <i class="fas fa-cube"></i> {{ __('messages.all_products') }}
                </a>
                @foreach($categories as $category)
                    <a href="{{ url('/?category=' . $category->id) }}" class="category-btn {{ request('category') == $category->id ? 'active' : '' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
                <div class="ms-lg-3 mt-2 mt-lg-0">
                    <form action="{{ url('/') }}" method="GET" class="d-inline" id="sortForm">
                        @if(request('q'))<input type="hidden" name="q" value="{{ request('q') }}">@endif
                        @if(request('category'))<input type="hidden" name="category" value="{{ request('category') }}">@endif
                        <label class="small text-muted me-1">{{ __('messages.sort') }}</label>
                        <select name="sort" class="form-select form-select-sm d-inline-block w-auto" onchange="this.form.submit()">
                            <option value="newest" {{ ($sort ?? '') === 'newest' ? 'selected' : '' }}>{{ __('messages.newest') }}</option>
                            <option value="price_asc" {{ ($sort ?? '') === 'price_asc' ? 'selected' : '' }}>{{ __('messages.price_low_to_high') }}</option>
                            <option value="price_desc" {{ ($sort ?? '') === 'price_desc' ? 'selected' : '' }}>{{ __('messages.price_high_to_low') }}</option>
                            <option value="name_asc" {{ ($sort ?? '') === 'name_asc' ? 'selected' : '' }}>{{ __('messages.name_a_to_z') }}</option>
                            <option value="name_desc" {{ ($sort ?? '') === 'name_desc' ? 'selected' : '' }}>{{ __('messages.name_z_to_a') }}</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="row" id="productsContainer">
            @if($products->isEmpty())
                <div class="col-12">
                    <div class="no-products">
                        <i class="fas fa-inbox"></i>
                        <h3>{{ __('messages.no_products') }}</h3>
                        <p>{{ __('messages.no_products_msg') }}</p>
                    </div>
                </div>
            @else
                @foreach($products as $product)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4 product-item" data-category="{{ $product->Category_id }}">
                        <div class="product-card">
                            <!-- Product Image -->
                            <a href="{{ route('product.show', $product) }}" class="text-decoration-none" style="display: block;">
                                <div class="product-image-container position-relative">
                                    @if($product->images->count())
                                        <div id="carouselProduct{{ $product->id }}" class="carousel slide h-100" data-bs-ride="carousel">
                                            <div class="carousel-inner h-100">
                                                @foreach($product->images as $img)
                                                    <div class="carousel-item @if($loop->first) active @endif h-100">
                                                        <img src="{{ filter_var($img->image_path, FILTER_VALIDATE_URL) ? $img->image_path : asset($img->image_path) }}" class="d-block w-100" alt="{{ $product->name }}">
                                                    </div>
                                                @endforeach
                                            </div>
                                            @if($product->images->count() > 1)
                                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselProduct{{ $product->id }}" data-bs-slide="prev" onclick="event.stopPropagation();">
                                                    <span class="carousel-control-prev-icon"></span>
                                                </button>
                                                <button class="carousel-control-next" type="button" data-bs-target="#carouselProduct{{ $product->id }}" data-bs-slide="next" onclick="event.stopPropagation();">
                                                    <span class="carousel-control-next-icon"></span>
                                                </button>
                                            @endif
                                        </div>
                                    @else
                                        <img src="https://via.placeholder.com/300x250?text=No+Image" class="w-100 h-100" alt="No Image">
                                    @endif
                                    <div class="product-badge {{ $product->quantity < 1 ? 'bg-danger' : '' }}">
                                        {{ $product->quantity < 1 ? __('messages.out_of_stock') : __('messages.in_stock') }}
                                    </div>
                                </div>
                            </a>

                            <!-- Product Info -->
                            <div class="product-body">
                                <span class="category-badge">{{ optional($product->category)->name ?? 'Uncategorized' }}</span>
                                <h6 class="product-name"><a href="{{ route('product.show', $product) }}" class="text-dark text-decoration-none">{{ $product->name }}</a></h6>
                                <p class="product-description">{{ $product->description }}</p>

                                <div class="product-info">
                                    <i class="fas fa-box"></i> Stock: <strong>{{ $product->quantity }}</strong>
                                    @if($product->quantity > 0 && $product->quantity < 5)
                                        <span class="text-danger small ms-1">(Only {{ $product->quantity }} left!)</span>
                                    @endif
                                </div>

                                <div class="product-price">
                                    ${{ number_format($product->price, 2) }}
                                </div>

                                @if($product->hasVisibleOffer())
                                    @include('partials.offer_card', ['offer' => $product->offer])
                                @endif

                                <!-- Action Buttons -->
                                <div class="product-actions">
                                    @auth
                                        @if($product->quantity < 1)
                                            <a href="{{ route('product.show', $product) }}#notify-me" class="btn-add-cart w-100 text-decoration-none text-center d-flex align-items-center justify-content-center" style="background: #28a745;">
                                                <i class="fas fa-bell"></i> {{ __('messages.notify_me') }}
                                            </a>
                                        @else
                                        <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-grow-1">
                                            @csrf
                                            <button type="submit" class="btn-add-cart w-100 {{ isset($cartProductIds[$product->id]) ? 'btn-in-cart' : '' }}">
                                                @if(isset($cartProductIds[$product->id]))
                                                    <i class="fas fa-check"></i> In Cart ({{ $cartQuantities[$product->id] ?? 1 }})
                                                @else
                                                    <i class="fas fa-shopping-cart"></i> {{ __('messages.add_to_cart') }}
                                                @endif
                                            </button>
                                        </form>
                                        @endif
                                        <form action="{{ route('favorites.toggle', $product) }}" method="POST" class="favorite-form">
                                            @csrf
                                            <button type="submit" class="btn-wishlist {{ isset($favoriteProductIds[$product->id]) ? 'active' : '' }}" title="{{ isset($favoriteProductIds[$product->id]) ? __('messages.remove_from_favorites') : __('messages.add_to_favorites') }}">
                                                <i class="{{ isset($favoriteProductIds[$product->id]) ? 'fas' : 'far' }} fa-heart"></i>
                                            </button>
                                        </form>
                                    @else
                                        @if($product->quantity < 1)
                                            <a href="{{ route('product.show', $product) }}#notify-me" class="btn-add-cart text-decoration-none text-center d-flex align-items-center justify-content-center" style="flex: 1; background: #28a745;">
                                                <i class="fas fa-bell"></i> {{ __('messages.notify_me') }}
                                            </a>
                                        @else
                                            <a href="{{ route('login') }}?redirect={{ urlencode(request()->fullUrl()) }}" class="btn-add-cart text-decoration-none text-center d-flex align-items-center justify-content-center" style="flex: 1;">
                                                <i class="fas fa-sign-in-alt"></i> {{ __('messages.sign_in_to_add') }}
                                            </a>
                                        @endif
                                        <a href="{{ route('login') }}?redirect={{ urlencode(request()->fullUrl()) }}" class="btn-wishlist" title="{{ __('messages.sign_in_to_favorites') }}">
                                            <i class="far fa-heart"></i>
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        @if($products->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $products->links() }}
            </div>
        @endif
    </div>

    <!-- Footer -->
    <footer class="footer-custom">
        <div class="container">
            <div class="row">
                <div class="col-md-3 mb-4">
                    <h6>{{ __('messages.about_us') }}</h6>
                    <p>{{ __('messages.about_desc') }}</p>
                </div>
                <div class="col-md-3 mb-4">
                    <h6>{{ __('messages.quick_links') }}</h6>
                    <ul style="list-style: none; padding: 0;">
                        <li><a href="/">{{ __('messages.nav_home') }}</a></li>
                        <li><a href="{{ route('contact') }}">{{ __('messages.contact_us') }}</a></li>
                        <li><a href="{{ route('faq') }}">{{ __('messages.faq') }}</a></li>
                        <li><a href="{{ route('order.track.show') }}">{{ __('messages.track_order') }}</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h6>{{ __('messages.newsletter') }}</h6>
                    @if(session('newsletter_success'))
                        <p class="small text-success">{{ session('newsletter_success') }}</p>
                    @else
                    <form action="{{ route('newsletter.subscribe') }}" method="POST" class="d-flex gap-1">
                        @csrf
                        <input type="email" name="email" class="form-control form-control-sm" placeholder="{{ __('messages.email_placeholder') }}" required>
                        <button type="submit" class="btn btn-danger btn-sm" style="background: #DC143C; border-color: #DC143C; font-weight: 600;">{{ __('messages.subscribe') }}</button>
                    </form>
                    <p class="small text-muted mt-1 mb-0">{{ __('messages.newsletter_desc') }}</p>
                    @endif
                </div>
                <div class="col-md-3 mb-4">
                    <h6>{{ __('messages.follow_us') }}</h6>
                    <div class="social-links">
                        <a href="https://www.facebook.com/gizmosudan/?locale=ar_AR" target="_blank" rel="noopener noreferrer" class="me-3" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://x.com/gizmosudan" target="_blank" rel="noopener noreferrer" class="me-3" title="X (Twitter)"><i class="fab fa-x-twitter"></i></a>
                        <a href="https://www.instagram.com/gizmosudan" target="_blank" rel="noopener noreferrer" class="me-3" title="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="https://www.tiktok.com/@gizmosudan" target="_blank" rel="noopener noreferrer" class="me-3" title="TikTok"><i class="fab fa-tiktok"></i></a>
                        <a href="https://wa.me/249919001000" target="_blank" rel="noopener noreferrer" class="me-3" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>
            <hr class="border-secondary">
            <div class="text-center">
                <p>&copy; {{ __('messages.copyright') }} | <a href="#">{{ __('messages.privacy_policy') }}</a> | <a href="#">{{ __('messages.terms_of_service') }}</a></p>
            </div>
        </div>
    </footer>

    <script>
        (function(){
            function runOfferCountdowns(){
                document.querySelectorAll('.offer-card-modern__countdown-value[data-ends-at]').forEach(function(el){
                    var end = new Date(el.getAttribute('data-ends-at')).getTime();
                    function upd(){
                        var now = Date.now();
                        if(end <= now){ el.textContent = 'Expired'; return; }
                        var d = Math.floor((end - now) / 86400000);
                        var h = Math.floor(((end - now) % 86400000) / 3600000);
                        var m = Math.floor(((end - now) % 3600000) / 60000);
                        var s = Math.floor(((end - now) % 60000) / 1000);
                        el.textContent = (d > 0 ? d + 'd ' : '') + String(h).padStart(2,'0') + ':' + String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
                    }
                    upd();
                    if(!el._offerTimer) el._offerTimer = setInterval(upd, 1000);
                });
            }
            if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', runOfferCountdowns);
            else runOfferCountdowns();
        })();
    </script>

    <!-- Floating Action Buttons Container -->
    <div id="floatingActionsContainer" class="floating-actions-container">
        <!-- Dark Mode Toggle Button -->
        <button id="darkModeFloatingBtn" class="floating-btn" title="Dark Mode" aria-label="Toggle dark mode">
            <i class="fas fa-moon"></i>
        </button>


    </div>

    <!-- Dark Mode and Language Switcher Scripts -->
    <script>
        // Dark Mode Implementation
        const DarkMode = {
            STORAGE_KEY: 'gizmo-store-dark-mode',
            DARK_CLASS: 'dark-mode',
            FLOATING_BTN_ID: 'darkModeFloatingBtn',

            init() {
                this.applyStoredPreference();
                this.setupToggleListener();
                this.setupSystemPreferenceListener();
            },

            applyStoredPreference() {
                const stored = localStorage.getItem(this.STORAGE_KEY);
                
                if (stored !== null) {
                    this.setDarkMode(stored === 'true');
                } else {
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    this.setDarkMode(prefersDark);
                    localStorage.setItem(this.STORAGE_KEY, String(prefersDark));
                }
            },

            setupToggleListener() {
                const toggle = document.getElementById(this.FLOATING_BTN_ID);
                if (toggle) {
                    toggle.addEventListener('click', (e) => {
                        e.preventDefault();
                        this.toggle();
                    });
                }
            },

            setupSystemPreferenceListener() {
                const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
                mediaQuery.addEventListener('change', (e) => {
                    if (localStorage.getItem(this.STORAGE_KEY) === null) {
                        this.setDarkMode(e.matches);
                    }
                });
            },

            toggle() {
                const isDarkMode = document.documentElement.classList.contains(this.DARK_CLASS);
                this.setDarkMode(!isDarkMode);
            },

            setDarkMode(enabled) {
                if (enabled) {
                    document.documentElement.classList.add(this.DARK_CLASS);
                } else {
                    document.documentElement.classList.remove(this.DARK_CLASS);
                }
                localStorage.setItem(this.STORAGE_KEY, String(enabled));
                this.updateToggleIcon();
            },

            updateToggleIcon() {
                const toggle = document.getElementById(this.FLOATING_BTN_ID);
                if (!toggle) return;

                const isDarkMode = document.documentElement.classList.contains(this.DARK_CLASS);
                const icon = toggle.querySelector('i');
                
                if (icon) {
                    if (isDarkMode) {
                        icon.classList.remove('fa-moon');
                        icon.classList.add('fa-sun');
                        toggle.setAttribute('title', 'Light Mode');
                        toggle.setAttribute('aria-label', 'Switch to light mode');
                    } else {
                        icon.classList.remove('fa-sun');
                        icon.classList.add('fa-moon');
                        toggle.setAttribute('title', 'Dark Mode');
                        toggle.setAttribute('aria-label', 'Switch to dark mode');
                    }
                }
            },

            isDarkMode() {
                return document.documentElement.classList.contains(this.DARK_CLASS);
            }
        };

        // Initialize dark mode when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => DarkMode.init());
        } else {
            DarkMode.init();
        }

        // Language Switcher
        document.addEventListener('DOMContentLoaded', function() {
            const langBtn = document.getElementById('languageSwitcherBtn');
            const dropdown = document.getElementById('languageDropdown');
            
            if (langBtn) {
                langBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
                });
                
                document.addEventListener('click', function(e) {
                    if (!e.target.closest('#languageSwitcherContainer')) {
                        dropdown.style.display = 'none';
                    }
                });
            }
        });
    </script>

    <!-- Floating Contact Button -->
    <a href="{{ route('contact') }}" class="floating-contact-btn" title="{{ __('messages.contact_us') }}" aria-label="Contact Us">
        <i class="fas fa-envelope"></i>
    </a>
</body>
</html>
