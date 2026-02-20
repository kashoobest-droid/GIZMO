<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>My Orders - GIZMO Store</title>
    <style>
        :root {
            /* Light Mode - Crisp & Clean */
            --bg-primary: #ffffff;
            --bg-secondary: #f4f7f9;
            --card-bg: #ffffff;
            --text-primary: #2d3436;
            --text-secondary: #636e72;
            --border-color: #edf2f7;
            --accent-color: #e31b23;
            --accent-hover: #b9151b;
            --nav-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        html.dark-mode {
            /* Dark Mode - Deep Midnight (Easier on eyes than pure black) */
            --bg-primary: #1a1c1e;
            --bg-secondary: #121416;
            --card-bg: #232629;
            --text-primary: #f1f2f6;
            --text-secondary: #b2bec3;
            --border-color: #343a40;
            --accent-color: #ff4d4d;
            --accent-hover: #ff6b6b;
            --nav-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }

        body {
            background-color: var(--bg-secondary);
            font-family: 'Inter', -apple-system, system-ui, sans-serif;
            color: var(--text-primary);
            transition: background-color 0.3s ease, color 0.3s ease;
            line-height: 1.6;
        }

        /* --- Navbar --- */
        .navbar-custom {
            background-color: var(--bg-primary);
            border-bottom: 2px solid var(--accent-color);
            box-shadow: var(--nav-shadow);
            padding: 0.75rem 0;
        }

        .navbar-brand {
            color: var(--accent-color) !important;
            font-weight: 800;
            letter-spacing: -0.5px;
            text-transform: uppercase;
        }

        .nav-link {
            color: var(--text-primary) !important;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.2s;
            padding: 0.5rem 1rem !important;
            border-radius: 8px;
        }

        .nav-link:hover {
            background: rgba(227, 27, 35, 0.05);
            color: var(--accent-color) !important;
        }

        /* --- Order Cards --- */
        .order-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 20px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .order-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.1);
            border-color: var(--accent-color);
        }

        .order-id-label {
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--text-secondary);
        }

        /* --- UI Elements --- */
        .badge-status {
            padding: 8px 16px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.75rem;
            letter-spacing: 0.3px;
        }

        .btn-primary-gizmo {
            background: var(--accent-color);
            color: white !important;
            border: none;
            padding: 12px 28px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-primary-gizmo:hover {
            background: var(--accent-hover);
            transform: scale(1.02);
        }

        .btn-outline-gizmo {
            border: 2px solid var(--accent-color);
            color: var(--accent-color);
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.2s;
        }

        .btn-outline-gizmo:hover {
            background: var(--accent-color);
            color: white;
        }

        .mode-toggle {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        /* --- Pagination Fixes --- */
        .page-link {
            background-color: var(--card-bg) !important;
            border-color: var(--border-color) !important;
            color: var(--text-primary) !important;
            margin: 0 3px;
        }

        .page-item.active .page-link {
            background-color: var(--accent-color) !important;
            border-color: var(--accent-color) !important;
            color: white !important;
        }

        .text-muted { color: var(--text-secondary) !important; }
    </style>
</head>
<body>
<nav class="navbar navbar-custom navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="/">
            <i class="fas fa-bolt-lightning me-2"></i> GIZMO STORE
        </a>
        
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#gizmoNavbar">
            <i class="fas fa-bars text-primary"></i>
        </button>

        <div class="collapse navbar-collapse" id="gizmoNavbar">
            <div class="navbar-nav ms-auto gap-2 align-items-center">
                <a class="nav-link" href="/">
                    <i class="fas fa-house-chimney me-1"></i> Home
                </a>
                <a class="nav-link" href="{{ route('cart.index') }}">
                    <i class="fas fa-cart-shopping me-1"></i> Cart
                </a>
                <a class="nav-link" href="{{ route('profile.edit') }}">
                    <i class="fas fa-circle-user me-1"></i> Profile
                </a>
                
                <form action="{{ route('logout') }}" method="POST" class="d-inline m-0">
                    @csrf
                    <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start">
                        <i class="fas fa-arrow-right-from-bracket me-1"></i> Logout
                    </button>
                </form>

                <button class="mode-toggle ms-lg-3" id="modeToggle" aria-label="Toggle Dark Mode">
                    <i class="fas fa-moon"></i>
                </button>
            </div>
        </div>
    </div>
</nav>

    <div class="container py-5">
        <header class="mb-5">
            <h2 class="display-6 fw-bold">My Orders</h2>
            <p class="text-muted">Track and manage your recent purchases</p>
        </header>

        @if($orders->isEmpty())
            <div class="order-card text-center py-5">
                <div class="opacity-50 mb-4">
                    <i class="fas fa-box-open fa-4 text-muted" style="font-size: 4rem;"></i>
                </div>
                <h4 class="fw-bold">No orders found</h4>
                <p class="text-muted mx-auto" style="max-width: 300px;">Looks like you haven't placed any orders yet. Ready to start shopping?</p>
                <a href="/" class="btn btn-primary-gizmo mt-3">Explore Catalog</a>
            </div>
        @else
            @foreach($orders as $order)
                <div class="order-card">
                    <div class="row align-items-center">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <span class="order-id-label">Reference</span>
                            <h5 class="mb-1 fw-bold">#{{ $order->id }}</h5>
                            <span class="text-muted small">
                                <i class="far fa-calendar-alt me-1"></i> {{ $order->created_at->format('M d, Y') }}
                            </span>
                        </div>
                        
                        <div class="col-6 col-md-3">
                            <span class="order-id-label d-block">Status</span>
                            <span class="badge bg-{{ $order->status_badge_class }} badge-status">
                                {{ __('messages.admin_status_' . $order->status) }}
                            </span>
                        </div>

                        <div class="col-6 col-md-2 text-md-center">
                            <span class="order-id-label d-block">Total</span>
                            <span class="fw-bold fs-5 text-accent">@currency($order->total)</span>
                        </div>
                        
                        <div class="col-md-3 text-md-end mt-3 mt-md-0">
                            <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-gizmo w-100">
                                View Details
                            </a>
                        </div>
                    </div>
                    
                    <div class="mt-3 pt-3 border-top d-flex align-items-center justify-content-between">
                        <div class="small text-muted">
                            <i class="fas fa-layer-group me-2"></i>{{ $order->items->count() }} Products
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="d-flex justify-content-center mt-5">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

    <script>
        const STORAGE_KEY = 'gizmo-dark-mode';
        const html = document.documentElement;
        const btn = document.getElementById('modeToggle');

        function setMode(isDark) {
            html.classList.toggle('dark-mode', isDark);
            btn.innerHTML = isDark ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
            localStorage.setItem(STORAGE_KEY, isDark);
        }

        btn.addEventListener('click', () => setMode(!html.classList.contains('dark-mode')));
        
        // Init
        if (localStorage.getItem(STORAGE_KEY) === 'true') setMode(true);
    </script>
</body>
</html>