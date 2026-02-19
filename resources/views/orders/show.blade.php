<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="/css/gizmo_sudan.css">
    <title>Order #{{ $order->id }} - GIZMO Store</title>
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

        .order-detail-card {
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            padding: 24px;
            margin-bottom: 20px;
        }

        .item-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
        }

        .badge {
            font-size: 0.85rem;
        }

        h2, h5 {
            color: var(--text-primary) !important;
        }

        html.dark-mode h2, html.dark-mode h5 {
            color: #ffffff !important;
        }

        .form-select {
            background-color: var(--bg-secondary);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        .form-select:focus {
            background-color: var(--bg-secondary);
            border-color: #DC143C;
            color: var(--text-primary);
            box-shadow: 0 0 0 0.2rem rgba(220, 20, 60, 0.25);
        }

        table {
            color: var(--text-primary);
        }

        .table-borderless td {
            border-color: var(--border-color);
        }

        .back-link {
            color: #DC143C;
            text-decoration: none;
            transition: all 0.3s;
        }

        .back-link:hover {
            color: #8B0000;
            text-decoration: underline;
        }

        hr {
            border-color: var(--border-color);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-custom navbar-expand-lg">
        <div class="container d-flex align-items-center justify-content-between">
            <a class="navbar-brand" href="/"><i class="fas fa-power-off"></i> GIZMO Store</a>
            <div class="d-flex gap-2 align-items-center flex-wrap">
                <a class="nav-link" href="{{ route('orders.index') }}">
                    <i class="fas fa-box-open"></i> My Orders
                </a>
                @auth
                    @php $u = auth()->user(); @endphp
                    @if(method_exists($u, 'isMasterAdmin') && $u->isMasterAdmin() || method_exists($u, 'hasAdminScope') && $u->hasAdminScope('orders'))
                        <a class="nav-link" href="{{ route('admin.orders.index') }}">
                            <i class="fas fa-cog"></i> Manage Orders
                        </a>
                    @endif
                @endauth
                <a class="nav-link" href="/">
                    <i class="fas fa-home"></i> Home
                </a>
                <a class="nav-link" href="{{ route('cart.index') }}">
                    <i class="fas fa-shopping-cart"></i> Cart
                </a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <a href="{{ route('orders.index') }}" class="back-link mb-3 d-inline-block"><i class="fas fa-arrow-left me-2"></i> Back to Orders</a>

        <div class="order-detail-card">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                <h2 style="color: #DC143C;">Order #{{ $order->id }}</h2>
                <div class="d-flex align-items-center gap-2">
                    @auth
                        @php $u = auth()->user(); @endphp
                        @if(method_exists($u, 'isMasterAdmin') && $u->isMasterAdmin() || method_exists($u, 'hasAdminScope') && $u->hasAdminScope('orders'))
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
                                <small class="text-muted">{{ $item->quantity }} × @currency($item->price)</small>
                            </td>
                            <td class="align-middle text-end">@currency($item->subtotal)</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <hr>
            <div class="d-flex justify-content-end">
                <h4 style="color: #DC143C;">Total: @currency($order->total)</h4>
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

    <!-- Dark Mode Initialization Script -->
    <script>
        const STORAGE_KEY = 'gizmo-store-dark-mode';
        const html = document.documentElement;

        // Initialize dark mode on page load
        function initializeDarkMode() {
            const isDarkMode = localStorage.getItem(STORAGE_KEY) === 'true';
            if (isDarkMode) {
                html.classList.add('dark-mode');
            } else {
                html.classList.remove('dark-mode');
            }
        }

        // Initialize immediately
        initializeDarkMode();

        // Listen for changes in localStorage from other tabs
        window.addEventListener('storage', (e) => {
            if (e.key === STORAGE_KEY) {
                if (e.newValue === 'true') {
                    html.classList.add('dark-mode');
                } else {
                    html.classList.remove('dark-mode');
                }
            }
        });
    </script>

</body>
</html>
