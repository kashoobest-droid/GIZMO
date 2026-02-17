<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <title>Order #{{ $order->id }} - KS Tech Store</title>
    <style>
        body { background-color: #f5f5f5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto; }
        .navbar-custom { background-color: #1a1a1a; }
        .navbar-custom .navbar-brand, .navbar-custom .nav-link { color: #DC143C !important; }
        .order-detail-card { background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); padding: 24px; margin-bottom: 20px; }
        .item-img { width: 60px; height: 60px; object-fit: cover; border-radius: 6px; }
    </style>
</head>
<body>
    <nav class="navbar navbar-custom navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/"><i class="fas fa-power-off"></i> GIZMO SD</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ route('orders.index') }}">My Orders</a>
                @auth
                    @if(auth()->user()->is_admin)
                        <a class="nav-link" href="{{ route('admin.orders.index') }}">Manage Orders</a>
                    @endif
                @endauth
                <a class="nav-link" href="/">Home</a>
                <a class="nav-link" href="{{ route('cart.index') }}">Cart</a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <a href="{{ route('orders.index') }}" class="text-muted text-decoration-none mb-3 d-inline-block"><i class="fas fa-arrow-left"></i> Back to Orders</a>

        <div class="order-detail-card">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                <h2>Order #{{ $order->id }}</h2>
                <div class="d-flex align-items-center gap-2">
                    @auth
                        @if(auth()->user()->is_admin)
                            <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="form-select form-select-sm d-inline-block" style="width:auto;" onchange="this.form.submit()">
                                    @foreach(['pending','processing','shipped','delivered','cancelled'] as $s)
                                        <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                    @endforeach
                                </select>
                            </form>
                        @endif
                    @endauth
                    <span class="badge bg-{{ $order->status_badge_class }} fs-6">{{ ucfirst($order->status) }}</span>
                </div>
            </div>
            <p class="text-muted mb-3">Placed on {{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>

            <h5 class="mb-2">Items</h5>
            <table class="table table-borderless">
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td class="align-middle" style="width:80px;">
                                @if($item->product && $item->product->images->first())
                                    <img src="{{ filter_var($item->product->images->first()->image_path, FILTER_VALIDATE_URL) ? $item->product->images->first()->image_path : asset($item->product->images->first()->image_path) }}" alt="" class="item-img">
                                @else
                                    <img src="https://via.placeholder.com/60?text=No+Image" alt="" class="item-img">
                                @endif
                            </td>
                            <td class="align-middle">
                                <strong>{{ $item->product_name }}</strong><br>
                                <small class="text-muted">{{ $item->quantity }} × ${{ number_format($item->price, 2) }}</small>
                            </td>
                            <td class="align-middle text-end">${{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <hr>
            <div class="d-flex justify-content-end">
                <h4 class="text-warning">Total: ${{ number_format($order->total, 2) }}</h4>
            </div>

            @if($order->shipping_address || $order->phone)
                <hr>
                <h5 class="mb-2">Shipping</h5>
                <p class="mb-0">{{ $order->formatShippingAddress() }}</p>
            @endif

            @if($order->notes)
                <hr>
                <h5 class="mb-2">Notes</h5>
                <p class="mb-0">{{ $order->notes }}</p>
            @endif
        </div>
    </div>
</body>
</html>
