<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <title>My Orders - GIZMO Store</title>
    <style>
        :root {
            --bg-primary: #ffffff;
            --bg-secondary: #f8f9fa;
            --card-bg: #ffffff;
            --text-primary: #1a1a1a;
            --text-secondary: #666666;
            --border-color: #dee2e6;
            --accent-color: #DC143C;
        }

        html.dark-mode {
            --bg-primary: #121212;
            --bg-secondary: #1d1d1d;
            --card-bg: #242424;
            --text-primary: #e8e8e8;
            --text-secondary: #a0a0a0;
            --border-color: #3a3a3a;
        }

        body {
            background-color: var(--bg-secondary);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: var(--text-primary);
            transition: background-color 0.3s, color 0.3s;
        }

        .navbar-custom {
            background-color: var(--bg-primary);
            border-bottom: 3px solid var(--accent-color);
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .navbar-custom .navbar-brand {
            color: var(--accent-color) !important;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .nav-link, .btn-link {
            color: var(--text-primary) !important;
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 500;
            transition: color 0.2s;
        }

        .nav-link:hover { color: var(--accent-color) !important; }

        .order-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .order-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.1);
            border-color: var(--accent-color);
        }

        .badge-status {
            padding: 6px 12px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
        }

        .mode-toggle {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        /* --- PAGINATION FIX --- */
        .pagination-wrapper {
            margin-top: 30px;
            display: flex;
            justify-content: center;
        }

        .pagination {
            gap: 5px;
        }

        .page-link {
            background-color: var(--card-bg);
            border-color: var(--border-color);
            color: var(--text-primary);
            border-radius: 8px !important;
            padding: 8px 16px;
            transition: all 0.2s;
        }

        .page-link:hover {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            color: #ffffff;
        }

        .page-item.active .page-link {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            color: #ffffff;
        }

        .page-item.disabled .page-link {
            background-color: var(--bg-secondary);
            color: var(--text-secondary);
            border-color: var(--border-color);
        }

        /* Laravel default SVG fix */
        .pagination svg {
            width: 20px;
            height: 20px;
        }
        
        /* Ensures the text summary "Showing X to Y" stays styled */
        nav[role="navigation"] .flex.items-center.justify-between {
            display: none !important; /* Hide tailwind style mobile summary */
        }
        /* --- END PAGINATION FIX --- */

        .btn-primary-gizmo {
            background: linear-gradient(135deg, #DC143C 0%, #8B0000 100%);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 600;
        }

        .text-muted { color: var(--text-secondary) !important; }
        h2 { font-weight: 700; }
    </style>
</head>
<body>
    <nav class="navbar navbar-custom navbar-expand-lg">
        <div class="container d-flex align-items-center justify-content-between">
            <a class="navbar-brand" href="/"><i class="fas fa-power-off"></i> GIZMO Store</a>
            
            <div class="d-flex gap-3 align-items-center">
                <button class="mode-toggle" id="modeToggle" title="Toggle Mode">
                    <i class="fas fa-moon"></i>
                </button>

                <div class="d-none d-lg-flex gap-2">
                    <a class="nav-link" href="/"><i class="fas fa-home"></i> Home</a>
                    <a class="nav-link" href="{{ route('cart.index') }}"><i class="fas fa-shopping-cart"></i> Cart</a>
                    <a class="nav-link" href="{{ route('profile.edit') }}"><i class="fas fa-user"></i> Profile</a>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline m-0">
                        @csrf
                        <button type="submit" class="btn-link nav-link border-0 bg-transparent">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h2><i class="fas fa-box-open me-2" style="color: var(--accent-color);"></i> My Orders</h2>
        </div>

        @if($orders->isEmpty())
            <div class="empty-orders shadow-sm text-center p-5">
                <i class="fas fa-shopping-bag fa-4x mb-4 text-muted"></i>
                <h4 class="fw-bold">No orders yet</h4>
                <p class="text-muted">Looks like you haven't made your choice yet.</p>
                <a href="/" class="btn btn-primary-gizmo mt-2">Browse Products</a>
            </div>
        @else
            @foreach($orders as $order)
                <div class="order-card">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <span class="text-muted small">ORDER ID</span>
                            <h5 class="mb-0 fw-bold">#{{ $order->id }}</h5>
                            <small class="text-muted"><i class="far fa-calendar-alt me-1"></i>{{ $order->created_at->format('M d, Y') }}</small>
                        </div>
                        
                        <div class="text-lg-end">
                            <span class="badge bg-{{ $order->status_badge_class }} badge-status mb-2 d-inline-block">
                                {{ ucfirst($order->status) }}
                            </span>
                            <div class="fw-bold fs-5" style="color: var(--accent-color);">@currency($order->total)</div>
                        </div>
                    </div>
                    
                    <hr class="my-3" style="border-color: var(--border-color); opacity: 0.5;">
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            <i class="fas fa-shopping-basket me-1"></i>
                            {{ $order->items->count() }} item(s)
                        </div>
                        <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-danger px-4">
                            Details
                        </a>
                    </div>
                </div>
            @endforeach

            <div class="pagination-wrapper">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

    <script>
        const STORAGE_KEY = 'gizmo-store-dark-mode';
        const html = document.documentElement;
        const modeToggle = document.getElementById('modeToggle');

        function updateModeToggle(isDark) {
            if (!modeToggle) return;
            modeToggle.innerHTML = isDark ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
        }

        function initializeDarkMode() {
            const isDarkMode = localStorage.getItem(STORAGE_KEY) === 'true';
            if (isDarkMode) {
                html.classList.add('dark-mode');
                updateModeToggle(true);
            } else {
                html.classList.remove('dark-mode');
                updateModeToggle(false);
            }
        }

        modeToggle.addEventListener('click', () => {
            const isDarkMode = html.classList.toggle('dark-mode');
            localStorage.setItem(STORAGE_KEY, isDarkMode);
            updateModeToggle(isDarkMode);
        });

        initializeDarkMode();
    </script>
</body>
</html>