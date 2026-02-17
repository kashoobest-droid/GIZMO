<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <title>{{ $product->name }} - KS Tech Store</title>
    <style>
        /* Light Mode (Default) */
        body { background-color: #f5f5f5; color: #333333; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto; }
        .navbar-custom { background-color: #1a1a1a; padding: 20px 0 !important; }
        .navbar-custom .navbar-nav { display: flex !important; flex-direction: row !important; gap: 20px; align-items: center; }
        .navbar-custom .nav-link { padding: 0 !important; margin: 0 !important; display: inline-flex; align-items: center; gap: 5px; }
        .navbar-custom .navbar-brand, .navbar-custom .nav-link { color: #DC143C !important; }
        .product-detail-img { max-height: 400px; object-fit: contain; }
        .bg-white { background: white; color: #333333; }
        h2, h3, h4, h5, h6 { color: #333333; }
        h4 { color: #000000 !important; }
        strong { color: #000000 !important; }
        .stock-quantity { color: #000000 !important; font-weight: 600; }
        .text-muted { color: #666666 !important; }
        .text-dark, .text-dark a { color: #333333 !important; }
        .card { background: white; color: #333333; border-color: #ddd; }
        .badge { background-color: #6c757d !important; }
        .btn-warning { background-color: #DC143C; border-color: #DC143C; }
        .btn-warning:hover { background-color: #8B0000; border-color: #8B0000; }
        .btn-outline-danger { color: #dc3545; border-color: #ddd; }
        .btn-outline-danger:hover { background-color: #f8d7da; border-color: #dc3545; }
        .btn-secondary { background-color: #f5f5f5; border-color: #ddd; color: #333333; }
        .btn-secondary:hover { background-color: #e9ecef; border-color: #999; }
        .btn-success { background-color: #28a745; border-color: #28a745; }
        .form-control, .form-select { background-color: #ffffff; border-color: #ddd; color: #333333; }
        .form-control:focus, .form-select:focus { border-color: #DC143C; box-shadow: 0 0 0 0.2rem rgba(220, 20, 60, 0.25); }
        .alert { background-color: #f8f9fa; border-color: #ddd; color: #333333; }
        .alert-info { background-color: #d1ecf1; border-color: #bee5eb; color: #0c5460; }
        .alert-success { background-color: #d4edda; border-color: #c3e6cb; color: #155724; }
        .bg-light { background-color: #f8f9fa !important; }
        .border { border-color: #ddd !important; }
        .carousel-slide { background: white; }
        .review-card { background: white; border-color: #eee; }
        .reaction-btn { border-color: #ddd; background: white; color: #666; }
        .reaction-btn:hover { border-color: #DC143C; color: #DC143C; }
        .reaction-btn.active { background: #DC143C; color: white; border-color: #DC143C; }
        .shadow-sm { box-shadow: 0 .125rem .25rem rgba(0,0,0,.075); }
        .text-danger { color: #dc3545 !important; }
        .text-success { color: #28a745 !important; }
        .text-warning { color: #DC143C !important; }
        .breadcrumb { background-color: transparent; }
        .breadcrumb-item { color: #333333; }
        .breadcrumb-item.active { color: #000000; }
        .breadcrumb-item a { color: #DC143C; text-decoration: none; }
        .breadcrumb-item a:hover { color: #8B0000; }
        h2 { color: #000000 !important; }
        .text-muted { color: #000000 !important; }
        .offer-card-modern { background: linear-gradient(135deg, #fff8f0 0%, #fff0e6 50%, #ffe8d9 100%); border-color: rgba(255, 153, 0, 0.25); }
        .offer-card-modern__label { color: #c2410c; }
        .offer-card-modern__title { color: #1a1a1a; }
        .offer-card-modern__desc { color: #666; }
        .offer-card-modern__countdown { color: #555; }
        .offer-card-modern__countdown-value { color: #c2410c; }
        .offer-card-modern__countdown-label { color: #666; }
        .offer-card-modern__meta { color: #666; }
        
        /* Dark Mode */
        html.dark-mode body { background-color: #0f0f0f; color: #e0e0e0; }
        html.dark-mode .bg-white { background: #1a1a1a; color: #e0e0e0; }
        html.dark-mode h2, html.dark-mode h3, html.dark-mode h4, html.dark-mode h5, html.dark-mode h6 { color: #ffffff; }
        html.dark-mode .text-muted { color: #b0b0b0 !important; }
        html.dark-mode .text-dark, html.dark-mode .text-dark a { color: #ffffff !important; }
        html.dark-mode .card { background: #1a1a1a; color: #e0e0e0; border-color: #2a2a2a; }
        html.dark-mode .badge { background-color: #2a2a2a !important; color: #b0b0b0 !important; }
        html.dark-mode .btn-secondary { background-color: #2a2a2a; border-color: #3a3a3a; color: #e0e0e0; }
        html.dark-mode .btn-secondary:hover { background-color: #3a3a3a; border-color: #4a4a4a; color: #ffffff; }
        html.dark-mode .btn-success { background-color: #1a4d2e; border-color: #28a745; }
        html.dark-mode .form-control, html.dark-mode .form-select { background-color: #2a2a2a; border-color: #3a3a3a; color: #e0e0e0; }
        html.dark-mode .form-control:focus, html.dark-mode .form-select:focus { border-color: #DC143C; box-shadow: 0 0 0 0.2rem rgba(220, 20, 60, 0.25); }
        html.dark-mode .alert { background-color: #1a1a1a; border-color: #2a2a2a; color: #e0e0e0; }
        html.dark-mode .alert-info { background-color: #1a2a3a; border-color: #2a4a5a; color: #90ccff; }
        html.dark-mode .alert-success { background-color: #1a2a1a; border-color: #2a5a2a; color: #90ee90; }
        html.dark-mode .bg-light { background-color: #2a2a2a !important; }
        html.dark-mode .border { border-color: #2a2a2a !important; }
        html.dark-mode .carousel-slide { background: #1a1a1a; }
        html.dark-mode .review-card { background: #1a1a1a; border-color: #2a2a2a; }
        html.dark-mode .reaction-btn { border-color: #3a3a3a; background: #1a1a1a; color: #b0b0b0; }
        html.dark-mode .reaction-btn:hover { border-color: #DC143C; color: #DC143C; }
        html.dark-mode .reaction-btn.active { background: #DC143C; color: white; border-color: #DC143C; }
        html.dark-mode .text-danger { color: #DC143C !important; }
        html.dark-mode .text-success { color: #90ee90 !important; }
        html.dark-mode .text-warning { color: #DC143C !important; }
        html.dark-mode .btn-outline-danger { color: #DC143C; border-color: #3a3a3a; }
        html.dark-mode .btn-outline-danger:hover { background-color: #5a1a1a; border-color: #DC143C; }
        html.dark-mode .btn-secondary:disabled { background-color: #3a3a3a; border-color: #2a2a2a; color: #b0b0b0; }
        html.dark-mode p { color: #e0e0e0; }
        html.dark-mode label { color: #e0e0e0; }
        html.dark-mode .form-label { color: #e0e0e0; }
        html.dark-mode .small { color: #b0b0b0; }
        html.dark-mode a { color: #DC143C; }
        html.dark-mode a:hover { color: #DC143C; }
        html.dark-mode .card-body { color: #e0e0e0; }
        html.dark-mode .card-title { color: #ffffff; }
        html.dark-mode .review-header { color: #e0e0e0; }
        html.dark-mode strong { color: #000000 !important; }
        html.dark-mode .review-card strong { color: #ffffff !important; }
        html.dark-mode .review-card .text-muted.small { color: #ffffff !important; }
        html.dark-mode .review-card .text-muted { color: #ffffff !important; }
        html.dark-mode .bg-light .text-muted.small { color: #ffffff !important; }
        html.dark-mode .navbar-menu { background-color: #0f0f0f; }
        html.dark-mode .navbar-toggler-icon { filter: brightness(1.5); }
        html.dark-mode .modal-content { background: #1a1a1a; color: #e0e0e0; border-color: #2a2a2a; }
        html.dark-mode .modal-header { border-color: #2a2a2a; }
        html.dark-mode .modal-footer { border-color: #2a2a2a; }
        html.dark-mode .modal-title { color: #ffffff; }
        html.dark-mode .btn-close { filter: invert(1); }
        html.dark-mode .breadcrumb { background-color: transparent; }
        html.dark-mode .breadcrumb-item { color: #e0e0e0; }
        html.dark-mode .breadcrumb-item.active { color: #000000; }
        html.dark-mode .breadcrumb-item a { color: #DC143C; text-decoration: none; }
        html.dark-mode .breadcrumb-item a:hover { color: #DC143C; }
        html.dark-mode h2 { color: #000000 !important; }
        html.dark-mode .text-muted { color: #000000 !important; }
        html.dark-mode .share-buttons { border-top-color: #2a2a2a; }
        html.dark-mode .share-buttons .small { color: #e0e0e0; }
        html.dark-mode .offer-card-modern { background: linear-gradient(135deg, #1f1f1f 0%, #262626 50%, #2d2d2d 100%); border-color: rgba(220, 20, 60, 0.35); }
        html.dark-mode .offer-card-modern__label { color: #DC143C; }
        html.dark-mode .offer-card-modern__title { color: #ffffff; }
        html.dark-mode .offer-card-modern__desc { color: #b0b0b0; }
        html.dark-mode .offer-card-modern__countdown { color: #e0e0e0; }
        html.dark-mode .offer-card-modern__countdown-value { color: #DC143C; }
        html.dark-mode .offer-card-modern__countdown-label { color: #b0b0b0; }
        html.dark-mode .offer-card-modern__meta { color: #b0b0b0; }
        .offer-card-modern { margin-top: 12px; border-radius: 12px; overflow: hidden; background: linear-gradient(135deg, #fff8f0 0%, #fff0e6 50%, #ffe8d9 100%); border: 1px solid rgba(255, 153, 0, 0.25); box-shadow: 0 2px 12px rgba(255, 153, 0, 0.08); }
        .offer-card-modern__inner { display: flex; gap: 14px; align-items: center; padding: 14px 16px; }
        .offer-card-modern__media { position: relative; flex-shrink: 0; width: 88px; min-width: 88px; height: 88px; border-radius: 10px; overflow: hidden; background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
        .offer-card-modern__img { display: block; width: 100%; height: 100%; object-fit: cover; object-position: center; }
        .offer-card-modern__badge { position: absolute; bottom: 0; left: 0; right: 0; text-align: center; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; background: rgba(255, 153, 0, 0.95); color: #fff; padding: 3px 4px; }
        .offer-card-modern__body { flex: 1; min-width: 0; }
        .offer-card-modern__label { display: inline-block; font-size: 0.75rem; font-weight: 600; color: #c2410c; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
        .offer-card-modern__title { font-size: 1.05rem; font-weight: 700; color: #1a1a1a; margin: 0 0 6px 0; line-height: 1.25; }
        .offer-card-modern__desc { font-size: 0.85rem; color: #666; margin: 0 0 8px 0; line-height: 1.35; }
        .offer-card-modern__countdown { font-size: 0.8rem; color: #555; }
        .offer-card-modern__countdown-value { font-weight: 700; color: #c2410c; font-variant-numeric: tabular-nums; }
        .offer-card-modern__meta { font-size: 0.8rem; color: #666; }
        
        /* Share buttons styling */
        .share-buttons { 
            display: flex; 
            gap: 8px; 
            flex-wrap: wrap; 
            align-items: center;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
        }
        .share-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            transition: all 0.3s ease;
            font-size: 1.1rem;
            text-decoration: none !important;
        }
        .share-btn-twitter { background: #000000; color: white; }
        .share-btn-twitter:hover { background: #333333; transform: translateY(-2px); }
        .share-btn-facebook { background: #000000; color: white; }
        .share-btn-facebook:hover { background: #333333; transform: translateY(-2px); }
        .share-btn-whatsapp { background: #000000; color: white; }
        .share-btn-whatsapp:hover { background: #333333; transform: translateY(-2px); }
        
        /* Review card styling */
        .review-card {
            background: white;
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }
        .review-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 10px;
        }
        i.fas.fa-comments { color: #000000; }
        .review-actions {
            display: flex;
            gap: 5px;
        }
        .review-actions .btn-sm {
            padding: 4px 8px;
            font-size: 0.75rem;
        }
        .review-reactions {
            display: flex;
            gap: 10px;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #f0f0f0;
        }
        .reaction-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 12px;
            border: 1px solid #ddd;
            border-radius: 20px;
            background: white;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s ease;
            color: #666;
        }
        .reaction-btn:hover {
            border-color: #DC143C;
            color: #DC143C;
        }
        .reaction-btn.active {
            background: #DC143C;
            color: white;
            border-color: #DC143C;
        }
        .btn-action-group {
            display: flex;
            gap: 8px;
        }
        
        /* Mobile Menu Styles */
        .navbar-toggler { 
            border: none; 
            font-size: 1.5rem; 
            color: #DC143C !important;
            padding: 0 !important;
            display: none;
        }
        
        .navbar-toggler:focus { outline: none; box-shadow: none; }
        .navbar-toggler-icon { width: 1.5em; height: 1.5em; }
        .navbar-menu { position: static; }
        
        @media (max-width: 767px) {
            .navbar-toggler { display: block !important; }
            
            .navbar-menu {
                position: fixed;
                top: 0;
                right: 0;
                width: 70vw;
                max-width: 300px;
                height: 100vh;
                background-color: #1a1a1a;
                display: flex;
                flex-direction: column;
                padding: 80px 20px 20px 20px;
                z-index: 999;
                transform: translateX(100%);
                transition: transform 0.3s ease;
                overflow-y: auto;
            }
            
            html[dir="rtl"] .navbar-menu {
                right: auto;
                left: 0;
                transform: translateX(-100%);
            }
            
            .navbar-menu.show {
                transform: translateX(0) !important;
            }
            
            html[dir="rtl"] .navbar-menu.show {
                transform: translateX(0) !important;
            }
            
            body.menu-open {
                overflow: hidden;
            }
            
            .navbar-menu .nav-link {
                font-size: 1.2rem;
                padding: 15px 0 !important;
                display: flex;
                align-items: center;
                gap: 15px;
            }
        }
        
        @media (min-width: 768px) {
            .navbar-toggler { display: none !important; }
            .navbar-menu { 
                position: static !important;
                transform: translateX(0) !important;
                height: auto !important;
                width: auto !important;
                padding: 0 !important;
                flex-direction: row !important;
                background: none !important;
            }
        }
    </style>
    <script>
        // Apply dark mode preference from localStorage
        document.addEventListener('DOMContentLoaded', function() {
            const darkModeEnabled = localStorage.getItem('gizmo-store-dark-mode') === 'true';
            if (darkModeEnabled) {
                document.documentElement.classList.add('dark-mode');
            }
            
            // Mobile Menu Toggle
            const navbarToggle = document.getElementById('navbarToggle');
            const navbarMenu = document.getElementById('navbarMenu');
            const navLinks = document.querySelectorAll('.navbar-menu .nav-link');
            
            if (navbarToggle) {
                navbarToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    navbarMenu.classList.toggle('show');
                    document.body.classList.toggle('menu-open');
                });
                
                // Close menu when a link is clicked
                navLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        navbarMenu.classList.remove('show');
                        document.body.classList.remove('menu-open');
                    });
                });
                
                // Close menu when clicking outside
                document.addEventListener('click', function(e) {
                    if (navbarMenu.classList.contains('show') && 
                        !navbarMenu.contains(e.target) && 
                        !navbarToggle.contains(e.target)) {
                        navbarMenu.classList.remove('show');
                        document.body.classList.remove('menu-open');
                    }
                });
            }
        });
    </script>
</head>
<body>
    <nav class="navbar navbar-custom navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/"><i class="fas fa-power-off"></i> GIZMO SD</a>
            <button class="navbar-toggler d-md-none" type="button" id="navbarToggle">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="navbar-nav ms-auto navbar-menu" id="navbarMenu">
                <a class="nav-link" href="/"><i class="fas fa-home"></i> {{ __('messages.nav_home') }}</a>
                <a class="nav-link" href="{{ auth()->check() ? route('cart.index') : route('login') }}"><i class="fas fa-shopping-cart"></i> {{ __('messages.nav_cart') }}</a>
                <a class="nav-link" href="{{ auth()->check() ? route('favorites.index') : route('login') }}"><i class="far fa-heart"></i> {{ __('messages.nav_favorites') }}</a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        @include('partials.breadcrumbs', [
            'items' => [
                ['label' => 'Home', 'url' => url('/')],
                ['label' => optional($product->category)->name ?? 'Products', 'url' => $product->category ? url('/?category=' . $product->category->id) : url('/')],
                ['label' => $product->name, 'url' => ''],
            ]
        ])
        <a href="{{ url()->previous() }}" class="text-muted text-decoration-none mb-3 d-inline-block"><i class="fas fa-arrow-left"></i> {{ __('messages.product_back') }}</a>
        <div class="row">
            <div class="col-md-6">
                @if($product->images->count())
                    <div id="productCarousel" class="carousel slide bg-white rounded shadow-sm overflow-hidden">
                        <div class="carousel-inner">
                            @foreach($product->images as $img)
                                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                    <img src="{{ filter_var($img->image_path, FILTER_VALIDATE_URL) ? $img->image_path : asset($img->image_path) }}" class="d-block w-100 product-detail-img" alt="{{ $product->name }}">
                                </div>
                            @endforeach
                        </div>
                        @if($product->images->count() > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        @endif
                    </div>
                @else
                    <img src="https://via.placeholder.com/500x400?text=No+Image" class="img-fluid rounded shadow-sm" alt="">
                @endif
            </div>
            <div class="col-md-6">
                <div class="bg-white rounded shadow-sm p-4">
                    <span class="badge bg-secondary">{{ optional($product->category)->name ?? 'Uncategorized' }}</span>
                    <h2 class="mt-2">{{ $product->name }}</h2>
                    <p class="text-muted">{{ $product->description }}</p>
                    <p class="mb-2"><strong>{{ __('messages.product_stock') }}:</strong>
                        @if($product->quantity < 1)
                            <span class="text-danger">{{ __('messages.out_of_stock') }}</span>
                        @else
                            <span class="stock-quantity">{{ $product->quantity }}</span>
                            @if($product->quantity < 5)<span class="text-danger small">({{ __('messages.product_only_left', ['count' => $product->quantity]) }})</span>@endif
                        @endif
                    </p>
                    <h3 class="text-warning mb-4">${{ number_format($product->price, 2) }}</h3>
                    @if($product->hasVisibleOffer())
                        @include('partials.offer_card', ['offer' => $product->offer])
                    @endif
                    @auth
                        <div class="d-flex gap-2 mt-3">
                            @if($product->quantity < 1)
                                <button type="button" class="btn btn-secondary btn-lg flex-grow-1" disabled><i class="fas fa-times-circle"></i> {{ __('messages.out_of_stock') }}</button>
                            @else
                            <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-grow-1">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-lg w-100"><i class="fas fa-shopping-cart"></i> {{ __('messages.add_to_cart') }}</button>
                            </form>
                            @endif
                            <form action="{{ route('favorites.toggle', $product) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-lg"><i class="far fa-heart"></i> <span class="d-none d-sm-inline ms-2">{{ __('messages.add_to_favorites') }}</span></button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-warning btn-lg mt-3 w-100"><i class="fas fa-sign-in-alt"></i> {{ __('messages.sign_in_to_add') }}</a>
                    @endauth
                    @if($product->quantity < 1)
                        <div id="notify-me" class="mt-3 p-3 rounded border border-success bg-success bg-opacity-10">
                            <p class="mb-2 fw-bold text-success"><i class="fas fa-bell"></i> {{ __('messages.product_notify_title') }}</p>
                            <p class="small text-muted mb-2">{{ __('messages.product_notify_desc') }}</p>
                            <form action="{{ route('stock-notify.store', $product) }}" method="POST" class="d-flex gap-2 flex-wrap">
                                @csrf
                                <input type="email" name="email" class="form-control" style="max-width: 220px;" placeholder="{{ __('messages.profile_email') }}" required>
                                <button type="submit" class="btn btn-success"><i class="fas fa-bell"></i> {{ __('messages.product_notify_btn') }}</button>
                            </form>
                            @if(session('success'))<p class="small text-success mb-0 mt-2">{{ session('success') }}</p>@endif
                            @if(session('info'))<p class="small text-info mb-0 mt-2">{{ session('info') }}</p>@endif
                        </div>
                    @endif
                    
                    <!-- Rate this product (only for verified buyers) -->
                    <div id="rate-product" class="mt-4 p-3 rounded border bg-light">
                        <h5 class="mb-2"><i class="fas fa-star text-warning"></i> {{ __('messages.product_rate_title') }}</h5>
                        @php $avg = $product->averageRating(); @endphp
                        @if($product->reviews->count() > 0)
                            <p class="mb-3 small"><span class="text-warning">@for($i=1;$i<=5;$i++)<i class="{{ $i <= round($avg) ? 'fas' : 'far' }} fa-star"></i>@endfor</span> {{ number_format($avg, 1) }} ({{ $product->reviews->count() }} {{ __('messages.product_review' . ($product->reviews->count() !== 1 ? 's' : '')) }})</p>
                        @endif
                        @auth
                            @if($product->hasPurchasedBy(auth()->user()))
                                <form action="{{ route('review.store', $product) }}" method="POST">
                                    @csrf
                                    <div class="mb-2">
                                        <label class="form-label small">{{ __('messages.product_rating') }}</label>
                                        <div class="d-flex gap-1 align-items-center" id="rating-stars">
                                            @for($i=1;$i<=5;$i++)
                                                <label class="mb-0" style="cursor:pointer;" title="{{ $i }} {{ __('messages.product_star' . ($i !== 1 ? 's' : '')) }}">
                                                    <input type="radio" name="rating" value="{{ $i }}" class="d-none" {{ old('rating') == $i ? 'checked' : '' }} required>
                                                    <i class="far fa-star text-warning fs-5 rating-star" data-value="{{ $i }}"></i>
                                                </label>
                                            @endfor
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small">{{ __('messages.product_review_label') }}</label>
                                        <textarea name="comment" class="form-control form-control-sm" rows="2" placeholder="{{ __('messages.product_review_placeholder') }}">{{ old('comment') }}</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-warning btn-sm"><i class="fas fa-paper-plane"></i> {{ __('messages.product_submit_review') }}</button>
                                </form>
                                @if(session('review_success'))<p class="small text-success mt-2 mb-0"><i class="fas fa-check-circle"></i> {{ session('review_success') }}</p>@endif
                            @else
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle"></i> <strong>{{ __('messages.product_verified_only') }}</strong> 
                                    <br><small class="mt-1 d-block">{{ __('messages.product_purchase_msg') }}</small>
                                </div>
                            @endif
                        @else
                            <p class="small mb-0"><a href="{{ route('login') }}">{{ __('messages.nav_sign_in') }}</a> {{ __('messages.product_sign_in_review') }}</p>
                        @endauth
                    </div>

                    <div class="share-buttons">
                        <span class="small text-muted fw-bold">{{ __('messages.product_share') }}:</span>
                        @php $productUrl = route('product.show', $product); $productName = $product->name; @endphp
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode($productUrl) }}&text={{ urlencode($productName) }}" target="_blank" rel="noopener" class="share-btn share-btn-twitter" title="Share on X/Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($productUrl) }}" target="_blank" rel="noopener" class="share-btn share-btn-facebook" title="Share on Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://wa.me/?text={{ urlencode($productName . ' ' . $productUrl) }}" target="_blank" rel="noopener" class="share-btn share-btn-whatsapp" title="Share on WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>
        </div>

        @if(isset($relatedProducts) && $relatedProducts->isNotEmpty())
        <div class="mt-5">
            <h4 class="mb-3">{{ __('messages.product_also_like') }}</h4>
            <div class="row g-3">
                @foreach($relatedProducts as $rel)
                <div class="col-6 col-md-3">
                    <a href="{{ route('product.show', $rel) }}" class="text-decoration-none text-dark">
                        <div class="card h-100">
                            @if($rel->images->first())
                                <img src="{{ filter_var($rel->images->first()->image_path, FILTER_VALIDATE_URL) ? $rel->images->first()->image_path : asset($rel->images->first()->image_path) }}" class="card-img-top" style="height:160px;object-fit:cover" alt="">
                            @else
                                <img src="https://via.placeholder.com/200x160?text=No+Image" class="card-img-top" style="height:160px;object-fit:cover" alt="">
                            @endif
                            <div class="card-body py-2">
                                <h6 class="card-title small">{{ Str::limit($rel->name, 40) }}</h6>
                                <p class="mb-0 text-warning fw-bold">${{ number_format($rel->price, 2) }}</p>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="mt-5 bg-white rounded shadow-sm p-4">
            <h4 class="mb-4"><i class="fas fa-comments"></i> {{ __('messages.product_customer_reviews') }}</h4>
            @php $avg = $product->averageRating(); @endphp
            @if($product->reviews->count() > 0)
                <div class="mb-4 p-3 bg-light rounded">
                    <div class="d-flex gap-2 align-items-center">
                        <span class="text-warning fs-5">@for($i=1;$i<=5;$i++)<i class="{{ $i <= round($avg) ? 'fas' : 'far' }} fa-star"></i>@endfor</span>
                        <span class="fw-bold">{{ number_format($avg, 1) }}/5.0</span>
                        <span class="text-muted small">({{ $product->reviews->count() }} review{{ $product->reviews->count() !== 1 ? 's' : '' }})</span>
                    </div>
                </div>
                @foreach($product->reviews as $review)
                    <div class="review-card">
                        <div class="review-header">
                            <div class="flex-grow-1">
                                <div class="d-flex gap-2 align-items-center mb-2">
                                    <strong>{{ $review->user->name ?? 'User' }}</strong>
                                    <span class="text-warning small">
                                        @for($i=1;$i<=5;$i++)<i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>@endfor
                                    </span>
                                    <span class="text-muted small">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                                @if($review->comment)
                                    <p class="mb-0 text-muted">{{ $review->comment }}</p>
                                @endif
                            </div>
                            @if(auth()->check() && auth()->user()->id === $review->user_id)
                                <div class="review-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editReviewModal{{ $review->id }}" title="Edit review"><i class="fas fa-edit"></i></button>
                                    <form action="{{ route('review.destroy', $review) }}" method="POST" style="display: inline;" onsubmit="return confirm('{{ __('messages.product_delete_confirm') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete review"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Reactions -->
                        @auth
                            <div class="review-reactions">
                                <form action="{{ route('review.react', $review) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <input type="hidden" name="reaction_type" value="helpful">
                                    <button type="submit" class="reaction-btn {{ $review->userReaction(auth()->user()) && $review->userReaction(auth()->user())->reaction_type === 'helpful' ? 'active' : '' }}" title="Mark as helpful">
                                        <i class="fas fa-thumbs-up"></i>
                                        <span>{{ $review->helpfulCount() }}</span>
                                    </button>
                                </form>
                                <form action="{{ route('review.react', $review) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <input type="hidden" name="reaction_type" value="not_helpful">
                                    <button type="submit" class="reaction-btn {{ $review->userReaction(auth()->user()) && $review->userReaction(auth()->user())->reaction_type === 'not_helpful' ? 'active' : '' }}" title="Mark as not helpful">
                                        <i class="fas fa-thumbs-down"></i>
                                        <span>{{ $review->notHelpfulCount() }}</span>
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="review-reactions">
                                <span class="small text-muted"><a href="{{ route('login') }}">{{ __('messages.nav_sign_in') }}</a> {{ __('messages.product_rate_review') }}</span>
                            </div>
                        @endauth
                    </div>

                    <!-- Edit Review Modal -->
                    @if(auth()->check() && auth()->user()->id === $review->user_id)
                        <div class="modal fade" id="editReviewModal{{ $review->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">{{ __('messages.product_edit_review') }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('review.update', $review) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('messages.product_rating') }}</label>
                                                <div class="d-flex gap-1" id="editRatingStars{{ $review->id }}">
                                                    @for($i=1;$i<=5;$i++)
                                                        <label style="cursor:pointer; font-size: 1.5rem;">
                                                            <input type="radio" name="rating" value="{{ $i }}" {{ $review->rating == $i ? 'checked' : '' }} class="d-none">
                                                            <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star text-warning edit-rating-star" data-value="{{ $i }}" data-modal="{{ $review->id }}"></i>
                                                        </label>
                                                    @endfor
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('messages.product_review_label') }}</label>
                                                <textarea name="comment" class="form-control" rows="3">{{ $review->comment }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.profile_cancel') }}</button>
                                            <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> {{ __('messages.profile_save') }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle"></i> {{ __('messages.product_no_reviews') }}
                </div>
            @endif
        </div>
    </div>
    <script>
        // Rating stars interactivity for main review form
        (function(){
            var container = document.getElementById('rating-stars');
            if (!container) return;
            var stars = container.querySelectorAll('.rating-star');
            var radios = container.querySelectorAll('input[name="rating"]');
            function updateStars(val) {
                var v = parseInt(val, 10);
                stars.forEach(function(s) {
                    var sv = parseInt(s.getAttribute('data-value'), 10);
                    s.className = 'text-warning fs-5 rating-star ' + (sv <= v ? 'fas' : 'far') + ' fa-star';
                });
            }
            stars.forEach(function(star) {
                star.addEventListener('click', function(e) {
                    e.preventDefault();
                    var v = parseInt(this.getAttribute('data-value'), 10);
                    radios.forEach(function(r) { r.checked = (parseInt(r.value, 10) === v); });
                    updateStars(v);
                });
            });
            var checked = container.querySelector('input[name="rating"]:checked');
            if (checked) updateStars(checked.value);
        })();

        // Rating stars interactivity for edit review modals
        document.querySelectorAll('.edit-rating-star').forEach(function(star) {
            star.addEventListener('click', function(e) {
                e.preventDefault();
                var v = parseInt(this.getAttribute('data-value'), 10);
                var modalId = this.getAttribute('data-modal');
                var modalContainer = document.querySelector('#editReviewModal' + modalId + ' .modal-body');
                var radios = modalContainer.querySelectorAll('input[name="rating"]');
                var stars = document.querySelectorAll('.edit-rating-star[data-modal="' + modalId + '"]');
                
                radios.forEach(function(r) { 
                    r.checked = (parseInt(r.value, 10) === v); 
                });
                stars.forEach(function(s) {
                    var sv = parseInt(s.getAttribute('data-value'), 10);
                    s.className = (sv <= v ? 'fas' : 'far') + ' fa-star text-warning edit-rating-star';
                });
            });
        });

        // Offer countdown timer
        (function(){
            document.querySelectorAll('.offer-card-modern__countdown-value[data-ends-at]').forEach(function(el){
                var end = new Date(el.getAttribute('data-ends-at')).getTime();
                function upd(){
                    var now = Date.now();
                    if(end <= now){ el.textContent = 'Expired'; return; }
                    var d = Math.floor((end - now) / 86400000), h = Math.floor(((end - now) % 86400000) / 3600000), m = Math.floor(((end - now) % 3600000) / 60000), s = Math.floor(((end - now) % 60000) / 1000);
                    el.textContent = (d > 0 ? d + 'd ' : '') + String(h).padStart(2,'0') + ':' + String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
                }
                upd();
                if(!el._offerTimer) el._offerTimer = setInterval(upd, 1000);
            });
        })();
    </script>
</body>
</html>
