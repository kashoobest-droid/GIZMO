<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="https://res.cloudinary.com/dgrnbtgts/image/upload/v1771338287/gizmo_qsab1d.png">
    <link rel="shortcut icon" href="https://res.cloudinary.com/dgrnbtgts/image/upload/v1771338287/gizmo_qsab1d.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="/css/gizmo_sudan.css">
    <title>{{ __('messages.nav_favorites') }} - Gizmo SD Store</title>
    <style>
        /* Light Mode (Default) */
        body { background-color: #f5f5f5; color: #333333; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto; }
        .fav-card { background: #ffffff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); overflow: hidden; transition: transform 0.2s; border: 1px solid #e0e0e0; }
        .fav-card:hover { transform: translateY(-3px); }
        .fav-img { height: 180px; object-fit: cover; }
        .fav-card .p-3 { color: #333333; }
        .fav-card h6 { color: #333333; }
        .fav-card .text-dark { color: #333333 !important; }
        .badge { background-color: #e9ecef !important; color: #666666 !important; }
        .btn-remove-fav { color: #dc3545; }
        .btn-remove-fav:hover { color: #c82333; }
        .btn-outline-danger { color: #dc3545; border-color: #ddd; }
        .btn-outline-danger:hover { background-color: #f8d7da; border-color: #dc3545; }
        .btn-secondary:disabled { background-color: #e9ecef; border-color: #ddd; color: #666666; }
        h2 { color: #333333; }
        .text-muted { color: #666666 !important; }
        .bg-white { background-color: #ffffff !important; }
        
        /* Dark Mode */
        html.dark-mode body { background-color: #0f0f0f; color: #e0e0e0; }
        html.dark-mode .fav-card { background: #1a1a1a; border: 1px solid #2a2a2a; box-shadow: 0 2px 10px rgba(0,0,0,0.3); }
        html.dark-mode .fav-card .p-3 { color: #e0e0e0; }
        html.dark-mode .fav-card h6 { color: #ffffff; }
        html.dark-mode .fav-card .text-dark { color: #ffffff !important; }
        html.dark-mode .badge { background-color: #2a2a2a !important; color: #b0b0b0 !important; }
        html.dark-mode .btn-remove-fav { color: #DC143C; }
        html.dark-mode .btn-remove-fav:hover { color: #DC143C; }
        html.dark-mode .btn-outline-danger { color: #DC143C; border-color: #3a3a3a; }
        html.dark-mode .btn-outline-danger:hover { background-color: #5a1a1a; border-color: #DC143C; }
        html.dark-mode .btn-secondary:disabled { background-color: #3a3a3a; border-color: #2a2a2a; color: #b0b0b0; }
        html.dark-mode h2 { color: #ffffff; }
        html.dark-mode .text-muted { color: #b0b0b0 !important; }
        html.dark-mode .bg-white { background-color: #1a1a1a !important; }
        
        .navbar-custom { background-color: #ffffff; border-bottom: 3px solid #DC143C; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .navbar-custom .navbar-brand { color: #DC143C !important; font-size: 1.8rem; font-weight: bold; }
        .navbar-custom .nav-link { color: #333333 !important; margin: 0 10px; transition: color 0.3s; }
        .navbar-custom .nav-link:hover { color: #DC143C !important; }
        .navbar-custom .btn-link.nav-link { color: #333333 !important; }
        .navbar-custom .btn-link.nav-link:hover { color: #DC143C !important; }
        
        /* Mobile responsive navbar */
        .navbar-toggler { border: 1px solid #333333 !important; }
        .navbar-toggler:focus { box-shadow: 0 0 0 0.25rem rgba(220, 20, 60, 0.25) !important; }
        .navbar-toggler-icon { background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%23333333' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e") !important; }
        .navbar-custom .navbar-nav { flex-direction: row; }
        .navbar-custom .navbar-nav .nav-link { padding: 0 10px; white-space: nowrap; }
        
        @media (max-width: 991px) {
            .navbar-custom .navbar-nav { flex-direction: column; }
            .navbar-custom .navbar-nav .nav-link { padding: 0.75rem 0; border-bottom: 1px solid #e0e0e0; }
            html.dark-mode .navbar-custom .navbar-nav .nav-link { border-bottom-color: #2a2a2a; }
            .navbar-custom .navbar-nav .nav-link:last-child { border-bottom: none; }
        }
        
        /* Dark mode navbar */
        html.dark-mode .navbar-custom { background-color: #1a1a1a; border-bottom: 3px solid #DC143C; }
        html.dark-mode .navbar-custom .nav-link { color: #ffffff !important; }
        html.dark-mode .navbar-custom .btn-link.nav-link { color: #ffffff !important; }
        .btn-add-cart { background: #DC143C; color: white; border: none; }
        .btn-add-cart:hover { background: #8B0000; color: white; }
        .btn-warning { background-color: #DC143C; border-color: #DC143C; }
        .btn-warning:hover { background-color: #8B0000; border-color: #8B0000; }
        
        /* Floating Contact Button */
        .floating-contact-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #DC143C 0%, #8B0000 100%);
            border: none;
            border-radius: 50%;
            color: white;
            font-size: 24px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(220, 20, 60, 0.4);
            z-index: 999;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        .floating-contact-btn:hover {
            transform: scale(1.1) translateY(-5px);
            box-shadow: 0 6px 20px rgba(220, 20, 60, 0.6);
            color: white;
        }
        html.dark-mode .floating-contact-btn {
            background: linear-gradient(135deg, #DC143C 0%, #8B0000 100%);
        }
        html[dir="rtl"] .floating-contact-btn {
            left: 30px;
            right: auto;
        }
    </style>
    <script>
        // Apply dark mode preference from localStorage
        document.addEventListener('DOMContentLoaded', function() {
            const darkModeEnabled = localStorage.getItem('gizmo-store-dark-mode') === 'true';
            if (darkModeEnabled) {
                document.documentElement.classList.add('dark-mode');
            }
        });
    </script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-custom navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/"><i class="fas fa-power-off"></i> GIZMO SD</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="navbar-nav ms-auto">
                    <a class="nav-link" href="/"><i class="fas fa-home"></i> {{ __('messages.nav_home') }}</a>
                    <a class="nav-link" href="{{ route('cart.index') }}"><i class="fas fa-shopping-cart"></i> {{ __('messages.nav_cart') }}</a>
                    <a class="nav-link" href="{{ route('favorites.index') }}"><i class="fas fa-heart"></i> {{ __('messages.nav_favorites') }}</a>
                    <a class="nav-link" href="{{ route('profile.edit') }}"><i class="fas fa-user"></i> {{ __('messages.nav_profile') }}</a>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-link nav-link" style="text-decoration:none;"><i class="fas fa-sign-out-alt"></i> {{ __('messages.nav_logout') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <h2 class="mb-4"><i class="fas fa-heart text-danger"></i> {{ __('messages.nav_favorites') }}</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($favorites->isEmpty())
            <div class="bg-white rounded shadow-sm p-5 text-center">
                <i class="fas fa-heart fa-4x text-muted mb-3"></i>
                <h4>{{ __('messages.no_favorites') }}</h4>
                <p class="text-muted">{{ __('messages.no_favorites_msg') }}</p>
                <a href="/" class="btn btn-warning mt-3"><i class="fas fa-shopping-bag"></i> {{ __('messages.browse_products') }}</a>
            </div>
        @else
            <div class="row g-4">
                @foreach($favorites as $fav)
                    <div class="col-md-4 col-lg-3">
                        <div class="fav-card h-100">
                            <a href="{{ route('product.show', $fav->product) }}" class="text-decoration-none text-dark">
                                @if($fav->product->images->first())
                                    <img src="{{ filter_var($fav->product->images->first()->image_path, FILTER_VALIDATE_URL) ? $fav->product->images->first()->image_path : asset($fav->product->images->first()->image_path) }}" class="fav-img w-100" alt="{{ $fav->product->name }}">
                                @else
                                    <img src="https://via.placeholder.com/300x180?text=No+Image" class="fav-img w-100" alt="">
                                @endif
                                <div class="p-3">
                                    <span class="badge bg-light text-dark">{{ optional($fav->product->category)->name }}</span>
                                    <h6 class="mt-2">{{ $fav->product->name }}</h6>
                                    <p class="text-warning fw-bold mb-2">${{ number_format($fav->product->price, 2) }}</p>
                                </div>
                            </a>
                            <div class="p-3 pt-0 d-flex gap-2">
                                @if($fav->product->quantity < 1)
                                    <button type="button" class="btn btn-secondary btn-sm w-100" disabled>{{ __('messages.out_of_stock') }}</button>
                                @else
                                <form action="{{ route('cart.add', $fav->product) }}" method="POST" class="flex-grow-1">
                                    @csrf
                                    <button type="submit" class="btn btn-add-cart w-100 btn-sm"><i class="fas fa-cart-plus"></i> {{ __('messages.add_to_cart') }}</button>
                                </form>
                                @endif
                                <form action="{{ route('favorites.remove', $fav->product) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" title="Remove from favorites"><i class="fas fa-heart-broken"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    
    <!-- Floating Contact Button -->
    <a href="{{ route('contact') }}" class="floating-contact-btn" title="Contact Us" aria-label="Contact Us">
        <i class="fas fa-envelope"></i>
    </a>
</body>
</html>
