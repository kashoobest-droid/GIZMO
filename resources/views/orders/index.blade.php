<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="/css/gizmo_sudan.css">
    <title>My Orders - GIZMO Store</title>
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

        .navbar-custom .btn-link {
            color: var(--text-primary) !important;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 0.5rem 1rem !important;
            transition: color 0.3s;
        }

        .navbar-custom .btn-link:hover {
            color: #DC143C !important;
        }

        .order-card {
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            padding: 20px;
            margin-bottom: 16px;
            transition: all 0.3s;
        }

        .order-card:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border-color: #DC143C;
        }

        .empty-orders {
            text-align: center;
            padding: 60px 20px;
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            border-radius: 10px;
        }

        .btn-outline-danger {
            color: #DC143C;
            border-color: #DC143C;
        }

        .btn-outline-danger:hover {
            background-color: #DC143C;
            border-color: #DC143C;
            color: white;
        }

        .badge {
            font-size: 0.85rem;
        }

        h2, h4 {
            color: var(--text-primary) !important;
        }

        .text-muted {
            color: var(--text-secondary) !important;
        }

        html.dark-mode h2 {
            color: #ffffff !important;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-custom navbar-expand-lg">
        <div class="container d-flex align-items-center justify-content-between">
            <a class="navbar-brand" href="/"><i class="fas fa-power-off"></i> GIZMO Store</a>
            <div class="d-flex gap-2 align-items-center flex-wrap">
                <a class="nav-link" href="/">
                    <i class="fas fa-home"></i> Home
                </a>
                <a class="nav-link" href="{{ route('cart.index') }}">
                    <i class="fas fa-shopping-cart"></i> Cart
                </a>
                <a class="nav-link" href="{{ route('favorites.index') }}">
                    <i class="fas fa-heart"></i> Favorites
                </a>
                <a class="nav-link" href="{{ route('profile.edit') }}">
                    <i class="fas fa-user"></i> Profile
                </a>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn-link nav-link" style="text-decoration:none;">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <h2 class="mb-4"><i class="fas fa-box-open" style="color: #DC143C;"></i> My Orders</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($orders->isEmpty())
            <div class="empty-orders">
                <i class="fas fa-shopping-bag fa-4x mb-3" style="color: var(--text-secondary);"></i>
                <h4>No orders yet</h4>
                <p class="text-muted">Start shopping to see your orders here.</p>
                <a href="/" class="btn mt-3" style="background: linear-gradient(135deg, #DC143C 0%, #8B0000 100%); color: white; border: none; font-weight: 600;"><i class="fas fa-shopping-bag me-2"></i> Browse Products</a>
            </div>
        @else
            @foreach($orders as $order)
                <div class="order-card">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                        <div>
                            <h5 class="mb-1" style="color: #DC143C;">Order #{{ $order->id }}</h5>
                            <small class="text-muted">{{ $order->created_at->format('M d, Y H:i') }}</small>
                        </div>
                        <div>
                            <span class="badge bg-{{ $order->status_badge_class }}">{{ ucfirst($order->status) }}</span>
                            <span class="fw-bold ms-2" style="color: #DC143C; font-size: 1.1rem;">@currency($order->total)</span>
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
                    <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-danger mt-2"><i class="fas fa-eye me-1"></i> View Details</a>
                </div>
            @endforeach

            <div class="d-flex justify-content-center mt-4">
                {{ $orders->links() }}
            </div>
        @endif
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
