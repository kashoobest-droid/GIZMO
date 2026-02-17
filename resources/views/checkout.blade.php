<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="/css/gizmo_sudan.css">
        <title>Checkout - Gizmo SD Store</title>
    <style>
        body { background-color: #ffffff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto; color: #222222; }
        .navbar-custom { background-color: #ffffff; border-bottom: 3px solid #DC143C; }
        .navbar-custom .navbar-brand, .navbar-custom .nav-link { color: #222222 !important; }
        .navbar-custom .navbar-brand { color: #DC143C !important; }
        .checkout-card { background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
        .cart-item-img { width: 60px; height: 60px; object-fit: cover; border-radius: 6px; }
        .btn-place-order { background: linear-gradient(135deg, #DC143C 0%, #8B0000 100%); border: none; color: white; font-weight: 600; padding: 12px; }
        .btn-place-order:hover { background: #8B0000; color: white; }
    </style>
</head>
<body>
    <nav class="navbar navbar-custom navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/"><i class="fas fa-power-off"></i> Gizmo SD</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="/">Home</a>
                <a class="nav-link" href="{{ route('cart.index') }}">Cart</a>
                <a class="nav-link" href="{{ route('profile.edit') }}">Profile</a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <h2 class="mb-4"><i class="fas fa-check-circle text-warning"></i> Checkout</h2>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="checkout-card p-4 mb-4">
                    <h5 class="mb-3">Shipping Address</h5>
                    <p class="text-muted mb-0">{{ auth()->user()->formatShippingAddress() }}</p>
                    @if(auth()->user()->phone)
                        <p class="mb-0 mt-1"><i class="fas fa-phone"></i> {{ auth()->user()->phone }}</p>
                    @endif
                    <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-secondary mt-2">Update Address</a>
                </div>

                <div class="checkout-card p-4">
                    <h5 class="mb-3">Order Items</h5>
                    @foreach($cartItems as $item)
                        <div class="d-flex align-items-center gap-3 py-2 border-bottom">
                            @if($item->product->images->first())
                                <img src="{{ filter_var($item->product->images->first()->image_path, FILTER_VALIDATE_URL) ? $item->product->images->first()->image_path : asset($item->product->images->first()->image_path) }}" alt="" class="cart-item-img">
                            @else
                                <img src="https://via.placeholder.com/60?text=No+Image" alt="" class="cart-item-img">
                            @endif
                            <div class="flex-grow-1">
                                <strong>{{ $item->product->name }}</strong><br>
                                <small class="text-muted">Qty: {{ $item->quantity }} × ${{ number_format($item->product->price, 2) }}</small>
                            </div>
                            <strong>${{ number_format($item->product->price * $item->quantity, 2) }}</strong>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-4">
                <div class="checkout-card p-4">
                    <h5 class="mb-3">Order Summary</h5>
                    <p class="d-flex justify-content-between mb-2"><span>Subtotal</span><span class="fw-bold text-warning">${{ number_format($subtotal, 2) }}</span></p>
                    @if(isset($coupon) && $coupon && $discount > 0)
                        <p class="d-flex justify-content-between mb-2 text-success"><span>Discount ({{ $coupon->code }})</span><span>-${{ number_format($discount, 2) }}</span></p>
                    @endif
                    <p class="d-flex justify-content-between mb-2"><span>Total</span><span class="fw-bold fs-5 text-warning">${{ number_format($total ?? $subtotal, 2) }}</span></p>
                    <hr>
                    <form action="{{ route('orders.store') }}" method="POST" id="checkoutForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small">Coupon code (optional)</label>
                            <div class="input-group input-group-sm">
                                <input type="text" name="coupon_code" class="form-control" placeholder="Enter code" value="{{ optional($coupon)->code ?? old('coupon_code') }}">
                            </div>
                            <p class="small text-muted mb-0">Enter your code and place order to apply.</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small">Order notes (optional)</label>
                            <textarea name="notes" class="form-control form-control-sm" rows="2" placeholder="Special instructions..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-place-order w-100">
                            <i class="fas fa-lock"></i> Place Order
                        </button>
                    </form>
                    <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary w-100 mt-2"><i class="fas fa-arrow-left"></i> Back to Cart</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
