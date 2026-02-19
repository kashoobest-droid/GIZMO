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
    <title>Manage Offers - GIZMO Store</title>
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

        .container {
            background-color: var(--bg-primary);
            border-radius: 10px;
            padding: 2rem !important;
            margin-top: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border: 1px solid var(--border-color);
        }

        h3 {
            color: #DC143C;
            font-weight: 700;
            font-size: 1.8rem;
        }

        .btn-success {
            background: linear-gradient(135deg, #DC143C 0%, #8B0000 100%);
            border: none;
            color: white;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #8B0000 0%, #5c0000 100%);
        }

        .btn-outline-primary {
            color: #DC143C;
            border-color: #DC143C;
        }

        .btn-outline-primary:hover {
            background-color: #DC143C;
            border-color: #DC143C;
            color: white;
        }

        .btn-outline-danger {
            color: #8B0000;
            border-color: #8B0000;
        }

        .btn-outline-danger:hover {
            background-color: #8B0000;
            border-color: #8B0000;
            color: white;
        }

        .card {
            background-color: var(--bg-primary);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        table {
            color: var(--text-primary);
        }

        thead th {
            background-color: var(--bg-secondary);
            color: var(--text-primary);
            border-color: var(--border-color);
            font-weight: 600;
        }

        tbody td {
            border-color: var(--border-color);
            vertical-align: middle;
        }

        .alert {
            background-color: var(--bg-secondary);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        .alert-warning {
            background-color: rgba(220, 20, 60, 0.1);
            border-color: #DC143C;
            color: var(--text-primary);
        }

        .alert-success {
            background-color: rgba(76, 175, 80, 0.1);
            border-color: #4CAF50;
            color: var(--text-primary);
        }

        .text-danger {
            color: #DC143C !important;
        }

        .pagination {
            margin-top: 1.5rem;
        }

        .pagination .page-link {
            background-color: var(--bg-secondary);
            color: var(--text-primary);
            border-color: var(--border-color);
        }

        .pagination .page-link:hover {
            background-color: #DC143C;
            border-color: #DC143C;
            color: white;
        }

        .pagination .page-item.active .page-link {
            background-color: #DC143C;
            border-color: #DC143C;
        }

        h3 i {
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-custom navbar-expand-lg">
        <div class="container d-flex align-items-center justify-content-between">
            <a class="navbar-brand" href="/"><i class="fas fa-power-off"></i> GIZMO Store</a>
            <div class="d-flex gap-2 align-items-center flex-wrap">
                <a class="nav-link" href="/">
                    <i class="fas fa-home"></i> Home
                </a>
                <a class="nav-link" href="/admin">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3><i class="fas fa-gift"></i> Manage Offers</h3>
            <a href="{{ route('offer.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-2"></i> Create Offer
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($offers->contains(fn($o) => !$o->product_id))
            <div class="alert alert-warning alert-dismissible fade show mb-3" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Not showing on the store?</strong> Offers only appear on the product they're linked to. Edit any offer with "Product: -" below and select a product so it shows on the home page.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag"></i> ID</th>
                            <th><i class="fas fa-tags"></i> Offer</th>
                            <th><i class="fas fa-gift"></i> Gift</th>
                            <th><i class="fas fa-box"></i> Product</th>
                            <th><i class="fas fa-clock"></i> Ends At</th>
                            <th><i class="fas fa-cog"></i> Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($offers as $o)
                            <tr>
                                <td><strong>{{ $o->id }}</strong></td>
                                <td>{{ $o->offer_name ?? '-' }}</td>
                                <td>{{ $o->gift_name }}</td>
                                <td>
                                    @if($o->product_id)
                                        <span style="color: #DC143C; font-weight: 500;">{{ $o->product->name }}</span>
                                    @else
                                        <span class="text-danger" title="Select a product in Edit so this offer shows on the store">
                                            <i class="fas fa-times-circle me-1"></i>— None (won't show)
                                        </span>
                                    @endif
                                </td>
                                <td>{{ optional($o->ends_at)->format('Y-m-d H:i') ?? '-' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('offer.edit', $o) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </a>
                                    <form action="{{ route('offer.destroy', $o) }}" method="POST" class="d-inline-block confirmable-form" data-confirm="Delete this offer?">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash me-1"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex justify-content-center">
            {{ $offers->links() }}
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
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
