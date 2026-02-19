<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="icon" type="image/png" href="https://res.cloudinary.com/dgrnbtgts/image/upload/v1771338287/gizmo_qsab1d.png">
    <link rel="shortcut icon" href="https://res.cloudinary.com/dgrnbtgts/image/upload/v1771338287/gizmo_qsab1d.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/css/gizmo_sudan.css">
    <title>Manage Coupons - GIZMO Store</title>
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

        .container {
            background-color: var(--bg-primary);
            border-radius: 10px;
            padding: 2rem !important;
            margin-top: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border: 1px solid var(--border-color);
        }

        h3 { color: #DC143C; font-weight: 700; font-size: 1.8rem; }

        .table thead th { font-weight: 600; }
    </style>
</head>
<body>
    <nav class="navbar navbar-custom navbar-expand-lg">
        <div class="container d-flex align-items-center justify-content-between">
            <a class="navbar-brand" href="/"><i class="fas fa-power-off"></i> GIZMO Store</a>
            <div class="d-flex gap-2 align-items-center flex-wrap">
                <a class="nav-link" href="/"> <i class="fas fa-home"></i> Home</a>
                <a class="nav-link" href="/admin"> <i class="fas fa-tachometer-alt"></i> Dashboard</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3><i class="fas fa-ticket-alt"></i> Manage Coupons</h3>
            <a href="{{ route('admin.coupons.create') }}" class="btn btn-warning"><i class="fas fa-plus me-2"></i>Create Coupon</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th><i class="fas fa-code"></i> Code</th>
                            <th><i class="fas fa-tags"></i> Type</th>
                            <th><i class="fas fa-percentage"></i> Value</th>
                            <th><i class="fas fa-money-bill-wave"></i> Min Purchase</th>
                            <th><i class="fas fa-clock"></i> Used</th>
                            <th><i class="fas fa-calendar-day"></i> Valid</th>
                            <th><i class="fas fa-cog"></i> Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($coupons as $c)
                            <tr>
                                <td><strong class="text-danger">{{ $c->code }}</strong></td>
                                <td>{{ $c->type === 'percent' ? 'Percent' : 'Fixed' }}</td>
                                <td>{{ $c->type === 'percent' ? $c->value . '%' : '$' . number_format($c->value, 2) }}</td>
                                <td>{{ $c->min_purchase ? '$' . number_format($c->min_purchase, 2) : '-' }}</td>
                                <td>{{ $c->used_count }}{{ $c->use_limit ? ' / ' . $c->use_limit : '' }}</td>
                                <td>
                                    @if($c->starts_at && now()->lessThan($c->starts_at))<span class="text-muted">Starts {{ $c->starts_at->format('M j') }}</span>
                                    @elseif($c->ends_at && now()->greaterThan($c->ends_at))<span class="text-danger">Expired</span>
                                    @else<span class="text-success">Active</span>@endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.coupons.destroy', $c) }}" method="POST" class="d-inline confirmable-form" data-confirm="Delete this coupon?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i> Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted">No coupons yet. <a href="{{ route('admin.coupons.create') }}">Create one</a>.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="pagination">{{ $coupons->links() }}</div>
    </div>
        <!-- Dark Mode Initialization Script -->
        <script>
            const STORAGE_KEY = 'gizmo-store-dark-mode';
            const html = document.documentElement;

            function initializeDarkMode() {
                const isDarkMode = localStorage.getItem(STORAGE_KEY) === 'true';
                if (isDarkMode) html.classList.add('dark-mode'); else html.classList.remove('dark-mode');
            }

            initializeDarkMode();

            window.addEventListener('storage', (e) => {
                if (e.key === STORAGE_KEY) {
                    if (e.newValue === 'true') html.classList.add('dark-mode'); else html.classList.remove('dark-mode');
                }
            });
        </script>
    </body>
    </html>
