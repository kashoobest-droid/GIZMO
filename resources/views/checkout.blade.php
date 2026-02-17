<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="/css/gizmo_sudan.css">
    <title>Checkout - Gizmo Store</title>
    <style>
        :root {
            --bg-primary: #ffffff;
            --bg-secondary: #f5f5f5;
            --text-primary: #1a1a1a;
            --text-secondary: #666666;
            --border-color: #ddd;
        }

        html.dark-mode {
            --bg-primary: #1a1a1a;
            --bg-secondary: #2a2a2a;
            --text-primary: #e8e8e8;
            --text-secondary: #a0a0a0;
            --border-color: #3a3a3a;
        }

        body {
            background-color: var(--bg-secondary);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto;
            color: var(--text-primary);
            transition: background-color 0.3s, color 0.3s;
        }

        .navbar-custom {
            background-color: var(--bg-primary);
            border-bottom: 3px solid #DC143C;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .navbar-custom .navbar-brand {
            color: #DC143C !important;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .navbar-custom .nav-link {
            color: var(--text-primary) !important;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 0.5rem 1rem !important;
            transition: color 0.3s;
        }

        .navbar-custom .nav-link:hover {
            color: #DC143C !important;
        }

        .navbar-custom .nav-link i {
            font-size: 1.1rem;
        }

        .mode-toggle {
            background: none;
            border: none;
            color: var(--text-primary);
            cursor: pointer;
            font-size: 1.3rem;
            padding: 0.5rem 1rem;
            transition: color 0.3s;
        }

        .mode-toggle:hover {
            color: #DC143C;
        }

        .checkout-card {
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            color: var(--text-primary);
        }

        .cart-item-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
        }

        .btn-place-order {
            background: linear-gradient(135deg, #DC143C 0%, #8B0000 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 12px;
            transition: all 0.3s;
        }

        .btn-place-order:hover {
            background: linear-gradient(135deg, #8B0000 0%, #5c0000 100%);
            color: white;
            box-shadow: 0 4px 8px rgba(220, 20, 60, 0.3);
        }

        .btn-outline-secondary {
            color: var(--text-primary);
            border-color: var(--border-color);
        }

        .btn-outline-secondary:hover {
            background-color: #DC143C;
            border-color: #DC143C;
            color: white;
        }

        .text-muted {
            color: var(--text-secondary) !important;
        }

        .border-bottom {
            border-color: var(--border-color) !important;
        }

        h2, h5 {
            color: var(--text-primary) !important;
        }

        .form-label, .form-control, textarea {
            color: var(--text-primary);
            background-color: var(--bg-secondary);
            border-color: var(--border-color);
        }

        .form-control:focus, textarea:focus {
            background-color: var(--bg-secondary);
            color: var(--text-primary);
            border-color: #DC143C;
            box-shadow: 0 0 0 0.2rem rgba(220, 20, 60, 0.25);
        }

        .alert {
            border-color: var(--border-color);
        }

        hr {
            border-color: var(--border-color);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-custom navbar-expand-lg">
        <div class="container d-flex align-items-center justify-content-between">
            <a class="navbar-brand" href="/"><i class="fas fa-power-off"></i> Gizmo Store</a>
            <button class="mode-toggle" id="modeToggle" title="Toggle Dark/Light Mode">
                <i class="fas fa-moon"></i>
            </button>
            <div class="d-flex gap-3 align-items-center">
                <a class="nav-link" href="/">
                    <i class="fas fa-home"></i> Home
                </a>
                <a class="nav-link" href="{{ route('cart.index') }}">
                    <i class="fas fa-shopping-cart"></i> Cart
                </a>
                <a class="nav-link" href="{{ route('profile.edit') }}">
                    <i class="fas fa-user"></i> Profile
                </a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <h2 class="mb-4"><i class="fas fa-check-circle" style="color: #DC143C;"></i> Checkout</h2>

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="checkout-card p-4 mb-4">
                    <h5 class="mb-3"><i class="fas fa-map-marker-alt" style="color: #DC143C;"></i> Shipping Address</h5>
                    <p class="text-muted mb-0">{{ auth()->user()->formatShippingAddress() }}</p>
                    @if(auth()->user()->phone)
                        <p class="mb-0 mt-2"><i class="fas fa-phone" style="color: #DC143C;"></i> {{ auth()->user()->phone }}</p>
                    @endif
                    <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-secondary mt-3">
                        <i class="fas fa-edit me-1"></i> Update Address
                    </a>
                </div>

                <div class="checkout-card p-4">
                    <h5 class="mb-3"><i class="fas fa-box" style="color: #DC143C;"></i> Order Items</h5>
                    @foreach($cartItems as $item)
                        <div class="d-flex align-items-center gap-3 py-2 border-bottom">
                            @if($item->product->images->first())
                                <img src="{{ filter_var($item->product->images->first()->image_path, FILTER_VALIDATE_URL) ? $item->product->images->first()->image_path : asset($item->product->images->first()->image_path) }}" alt="" class="cart-item-img">
                            @else
                                <img src="https://via.placeholder.com/60?text=No+Image" alt="" class="cart-item-img">
                            @endif
                            <div class="flex-grow-1">
                                <strong>{{ $item->product->name }}</strong><br>
                                <small class="text-muted">Qty: {{ $item->quantity }} × @currency($item->product->price)</small>
                            </div>
                            <strong>@currency($item->product->price * $item->quantity)</strong>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="col-lg-4">
                <div class="checkout-card p-4">
                    <h5 class="mb-3"><i class="fas fa-receipt" style="color: #DC143C;"></i> Order Summary</h5>
                    <p class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span class="fw-bold" style="color: #DC143C;">@currency($subtotal)</span>
                    </p>
                    @if(isset($coupon) && $coupon && $discount > 0)
                        <p class="d-flex justify-content-between mb-2 text-success">
                            <span>Discount ({{ $coupon->code }})</span>
                            <span>-@currency($discount)</span>
                        </p>
                    @endif
                    <p class="d-flex justify-content-between mb-2">
                        <span>Total</span>
                        <span class="fw-bold fs-5" style="color: #DC143C;">@currency($total ?? $subtotal)</span>
                    </p>
                    <hr>
                    <form action="{{ route('orders.store') }}" method="POST" id="checkoutForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small"><i class="fas fa-tag me-1"></i>Coupon code (optional)</label>
                            <div class="input-group input-group-sm">
                                <input type="text" name="coupon_code" class="form-control" placeholder="Enter code" value="{{ optional($coupon)->code ?? old('coupon_code') }}">
                            </div>
                            <p class="small text-muted mb-0 mt-1">Enter your code and place order to apply.</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small"><i class="fas fa-pen me-1"></i>Order notes (optional)</label>
                            <textarea name="notes" class="form-control form-control-sm" rows="2" placeholder="Special instructions..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-place-order w-100">
                            <i class="fas fa-lock me-2"></i> Place Order
                        </button>
                    </form>
                    <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                        <i class="fas fa-arrow-left me-2"></i> Back to Cart
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Dark Mode Script -->
    <script>
        const STORAGE_KEY = 'gizmo-store-dark-mode';
        const html = document.documentElement;
        const modeToggle = document.getElementById('modeToggle');

        // Initialize dark mode
        function initializeDarkMode() {
            const isDarkMode = localStorage.getItem(STORAGE_KEY) === 'true' || 
                             (!localStorage.getItem(STORAGE_KEY) && window.matchMedia('(prefers-color-scheme: dark)').matches);
            
            if (isDarkMode) {
                html.classList.add('dark-mode');
                updateModeToggle(true);
            } else {
                html.classList.remove('dark-mode');
                updateModeToggle(false);
            }
        }

        // Update toggle button
        function updateModeToggle(isDark) {
            modeToggle.innerHTML = isDark ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
            modeToggle.title = isDark ? 'Switch to Light Mode' : 'Switch to Dark Mode';
        }

        // Toggle dark mode
        modeToggle.addEventListener('click', () => {
            const isDarkMode = html.classList.toggle('dark-mode');
            localStorage.setItem(STORAGE_KEY, isDarkMode);
            updateModeToggle(isDarkMode);
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', initializeDarkMode);
    </script>
</body>
</html>
</body>
</html>
