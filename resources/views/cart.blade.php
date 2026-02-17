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
    <title>{{ __('messages.nav_cart') }} - KS Tech Store</title>
    <style>
        /* Light Mode (Default) */
        body { background-color: #ffffff; color: #333333; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto; }
        .cart-card { background: #ffffff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); overflow: hidden; border: 1px solid #e0e0e0; }
        .border-bottom { border-color: #e0e0e0 !important; }
        h2, h4, h5, h6 { color: #333333; }
        .text-muted { color: #666666 !important; }
        .form-control { background-color: #ffffff; border-color: #ddd; color: #333333; }
        .form-control:focus { background-color: #ffffff; border-color: #DC143C; color: #333333; box-shadow: 0 0 0 0.2rem rgba(220, 20, 60, 0.25); }
        .btn-outline-secondary { color: #666666; border-color: #ddd; }
        .btn-outline-secondary:hover { background-color: #f5f5f5; border-color: #999; color: #333333; }
        .alert { background-color: #ffffff; border-color: #ddd; color: #333333; }
        .alert-success { background-color: #d4edda; border-color: #c3e6cb; color: #155724; }
        .alert-danger { background-color: #f8d7da; border-color: #f5c6cb; color: #721c24; }
        .btn-remove { color: #dc3545; }
        .btn-remove:hover { color: #c82333; }
        
        /* Dark Mode */
        html.dark-mode body { background-color: #0f0f0f; color: #e0e0e0; }
        html.dark-mode .cart-card { background: #1a1a1a; border: 1px solid #2a2a2a; box-shadow: 0 2px 10px rgba(0,0,0,0.3); }
        html.dark-mode .border-bottom { border-color: #2a2a2a !important; }
        html.dark-mode h2, html.dark-mode h4, html.dark-mode h5, html.dark-mode h6 { color: #ffffff; }
        html.dark-mode .text-muted { color: #b0b0b0 !important; }
        html.dark-mode .form-control { background-color: #2a2a2a; border-color: #3a3a3a; color: #e0e0e0; }
        html.dark-mode .form-control:focus { background-color: #2a2a2a; border-color: #DC143C; color: #e0e0e0; }
        html.dark-mode .btn-outline-secondary { color: #b0b0b0; border-color: #3a3a3a; }
        html.dark-mode .btn-outline-secondary:hover { background-color: #3a3a3a; border-color: #4a4a4a; color: #ffffff; }
        html.dark-mode .alert { background-color: #1a1a1a; border-color: #2a2a2a; color: #e0e0e0; }
        html.dark-mode .alert-success { background-color: #1a2a1a; border-color: #2a5a2a; color: #90ee90; }
        html.dark-mode .alert-danger { background-color: #2a1a1a; border-color: #5a2a2a; color: #DC143C; }
        html.dark-mode .btn-remove { color: #DC143C; }
        html.dark-mode .btn-remove:hover { color: #DC143C; }
        
        .navbar-custom { background-color: #1a1a1a; box-shadow: 0 2px 5px rgba(0,0,0,0.1); border-bottom: 3px solid #DC143C; }
        .navbar-custom .navbar-brand { color: #DC143C !important; font-size: 1.8rem; font-weight: bold; }
        .navbar-custom .nav-link { color: #ffffff !important; margin: 0 10px; transition: color 0.3s; }
        .navbar-custom .nav-link:hover { color: #DC143C !important; }
        .navbar-custom .btn-link.nav-link { color: #ffffff !important; }
        .navbar-custom .btn-link.nav-link:hover { color: #DC143C !important; }
        
        /* Mobile responsive navbar */
        .navbar-toggler { border: 1px solid #ffffff !important; }
        .navbar-toggler:focus { box-shadow: 0 0 0 0.25rem rgba(220, 20, 60, 0.25) !important; }
        .navbar-toggler-icon { background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%23ffffff' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e") !important; }
        .navbar-custom .navbar-nav { flex-direction: row; }
        .navbar-custom .navbar-nav .nav-link { padding: 0 10px; white-space: nowrap; }
        
        @media (max-width: 991px) {
            .navbar-custom .navbar-nav { flex-direction: column; }
            .navbar-custom .navbar-nav .nav-link { padding: 0.75rem 0; border-bottom: 1px solid #3a3a3a; }
            html.dark-mode .navbar-custom .navbar-nav .nav-link { border-bottom-color: #2a2a2a; }
            .navbar-custom .navbar-nav .nav-link:last-child { border-bottom: none; }
        }
        
        /* Dark mode navbar */
        html.dark-mode .navbar-custom { background-color: #0a0a0a; border-bottom: 3px solid #DC143C; }
        .cart-item-img { width: 100px; height: 100px; object-fit: cover; border-radius: 8px; }
        .cart-total { font-size: 1.4rem; font-weight: bold; color: #DC143C; }
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
        <h2 class="mb-4"><i class="fas fa-shopping-cart text-warning"></i> {{ __('messages.nav_cart') }}</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if($cartItems->isEmpty())
            <div class="cart-card p-5 text-center">
                <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                <h4>{{ __('messages.cart_empty') }}</h4>
                <p class="text-muted">{{ __('messages.cart_empty_msg') }}</p>
                <a href="/" class="btn btn-warning mt-3"><i class="fas fa-shopping-bag"></i> {{ __('messages.continue_shopping') }}</a>
            </div>
        @else
            <div class="row">
                <div class="col-lg-8">
                    <div class="cart-card">
                        @foreach($cartItems as $item)
                            <div class="p-4 border-bottom d-flex align-items-center gap-3">
                                <div class="flex-shrink-0">
                                    @if($item->product->images->first())
                                        <img src="{{ filter_var($item->product->images->first()->image_path, FILTER_VALIDATE_URL) ? $item->product->images->first()->image_path : asset($item->product->images->first()->image_path) }}" alt="" class="cart-item-img">
                                    @else
                                        <img src="https://via.placeholder.com/100?text=No+Image" alt="" class="cart-item-img">
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $item->product->name }}</h6>
                                    <small class="text-muted">{{ optional($item->product->category)->name }}</small>
                                    <p class="mb-0 mt-1 text-warning fw-bold">${{ number_format($item->product->price, 2) }}</p>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <form action="{{ route('cart.update', $item) }}" method="POST" class="d-flex align-items-center gap-1">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->quantity }}" class="form-control form-control-sm" style="width:70px;">
                                        <button type="submit" class="btn btn-sm btn-outline-secondary"><i class="fas fa-sync-alt"></i></button>
                                    </form>
                                    <form action="{{ route('cart.remove', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('messages.confirm_remove') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-remove"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                                <div class="text-end" style="min-width:80px;">
                                    ${{ number_format($item->product->price * $item->quantity, 2) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="cart-card p-4">
                        <h5 class="mb-3">{{ __('messages.order_summary') }}</h5>
                        @php $subtotal = $cartItems->sum(fn($i) => $i->product->price * $i->quantity); @endphp
                        <p class="d-flex justify-content-between mb-2"><span>{{ __('messages.subtotal') }} ({{ $cartItems->sum('quantity') }} {{ __('messages.items') }})</span><span class="cart-total">${{ number_format($subtotal, 2) }}</span></p>
                        <hr>
                        <a href="{{ route('checkout') }}" class="btn btn-warning w-100 mb-2"><i class="fas fa-lock"></i> {{ __('messages.checkout') }}</a>
                        <a href="/" class="btn btn-outline-secondary w-100"><i class="fas fa-arrow-left"></i> {{ __('messages.continue_shopping') }}</a>
                    </div>
                </div>
            </div>
        @endif
    </div>
    
    <!-- Floating Contact Button -->
    <a href="{{ route('contact') }}" class="floating-contact-btn" title="Contact Us" aria-label="Contact Us">
        <i class="fas fa-envelope"></i>
    </a>
</body>
</html>
