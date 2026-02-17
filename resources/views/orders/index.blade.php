<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <title>My Orders - KS Tech Store</title>
    <style>
        body { background-color: #f5f5f5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto; }
        .navbar-custom { background-color: #1a1a1a; }
        .navbar-custom .navbar-brand, .navbar-custom .nav-link { color: #DC143C !important; }
        .order-card { background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); padding: 20px; margin-bottom: 16px; }
        .order-card:hover { box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .empty-orders { text-align: center; padding: 60px 20px; background: white; border-radius: 10px; }
    </style>
</head>
<body>
    <nav class="navbar navbar-custom navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/"><i class="fas fa-power-off"></i> GIZMO SD</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="/">Home</a>
                <a class="nav-link" href="{{ route('cart.index') }}">Cart</a>
                <a class="nav-link" href="{{ route('favorites.index') }}">Favorites</a>
                <a class="nav-link" href="{{ route('profile.edit') }}">Profile</a>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-link nav-link p-0" style="text-decoration:none;"><i class="fas fa-sign-out-alt"></i> Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <h2 class="mb-4"><i class="fas fa-box text-warning"></i> My Orders</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($orders->isEmpty())
            <div class="empty-orders">
                <i class="fas fa-shopping-bag fa-4x text-muted mb-3"></i>
                <h4>No orders yet</h4>
                <p class="text-muted">Start shopping to see your orders here.</p>
                <a href="/" class="btn btn-warning mt-3"><i class="fas fa-shopping-bag"></i> Browse Products</a>
            </div>
        @else
            @foreach($orders as $order)
                <div class="order-card">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                        <div>
                            <h5 class="mb-1">Order #{{ $order->id }}</h5>
                            <small class="text-muted">{{ $order->created_at->format('M d, Y H:i') }}</small>
                        </div>
                        <div>
                            <span class="badge bg-{{ $order->status_badge_class }}">{{ ucfirst($order->status) }}</span>
                            <span class="fw-bold text-warning ms-2">${{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small class="text-muted">
                            {{ $order->items->count() }} item(s)
                            @foreach($order->items->take(2) as $item)
                                {{ $item->product_name }}{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                            @if($order->items->count() > 2)
                                and {{ $order->items->count() - 2 }} more
                            @endif
                        </small>
                    </div>
                    <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-warning mt-2">View Details</a>
                </div>
            @endforeach

            <div class="d-flex justify-content-center mt-4">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</body>
</html>
