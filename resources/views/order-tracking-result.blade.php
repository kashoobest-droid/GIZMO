<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="https://res.cloudinary.com/dgrnbtgts/image/upload/v1771338287/gizmo_qsab1d.png">
    <link rel="shortcut icon" href="https://res.cloudinary.com/dgrnbtgts/image/upload/v1771338287/gizmo_qsab1d.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Order #{{ $order->id }} - Gizmo SD Store</title>
    <style>
        body { background: #f5f5f5; color: #222222; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; }
        .navbar-custom { background: #1a1a1a; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .navbar-custom .navbar-brand { color: #DC143C !important; font-size: 1.2rem; font-weight: bold; }
        .navbar-custom .nav-link { color: #ffffff !important; display:flex; align-items:center; gap:8px; }
        .card { border-radius: 8px; }
        html.dark-mode body { background: #0d0d0d !important; color: #e8e8e8 !important; }
        html.dark-mode .navbar-custom { background: #111 !important; }
        html.dark-mode .card { background: #222 !important; color: #e8e8e8 !important; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="/"><i class="fas fa-power-off"></i> Gizmo SD</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavRes">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavRes">
                <div class="navbar-nav ms-auto">
                    <a class="nav-link" href="/"><i class="fas fa-home me-2"></i> Home</a>
                    <a class="nav-link" href="{{ route('faq') }}"><i class="fas fa-question-circle me-2"></i> FAQ</a>
                    <a class="nav-link" href="{{ route('contact') }}"><i class="fas fa-envelope me-2"></i> Contact</a>
                    <a class="nav-link" href="{{ route('order.track.show') }}"><i class="fas fa-map-location-dot me-2"></i> Track Order</a>
                </div>
            </div>
        </div>
    </nav>
    <div class="container my-5">
        <h2 class="mb-4">Order #{{ $order->id }}</h2>
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <p class="mb-0"><strong>Status:</strong> <span class="badge bg-{{ $order->status_badge_class }}">{{ __('messages.admin_status_' . $order->status) }}</span></p>
                <p class="mb-0 mt-2"><strong>Total:</strong> ${{ number_format($order->total, 2) }}</p>
                @if($order->discount > 0)
                    <p class="mb-0 small text-success">Discount applied: -${{ number_format($order->discount, 2) }}</p>
                @endif
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header">Items</div>
            <ul class="list-group list-group-flush">
                @foreach($order->items as $item)
                    <li class="list-group-item d-flex justify-content-between">
                        <span>{{ $item->product_name }} × {{ $item->quantity }}</span>
                        <span>${{ number_format($item->subtotal, 2) }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
        <a href="{{ route('order.track.show') }}" class="btn btn-outline-secondary mt-3"><i class="fas fa-arrow-left me-2"></i> Track Another Order</a>
    </div>
</body>
</html>
