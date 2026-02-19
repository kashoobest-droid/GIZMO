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
    <title>Create Coupon - GIZMO Store</title>
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

        h3 { color: #DC143C; font-weight: 700; font-size: 1.8rem; margin-bottom: 1.5rem; }
    </style>
</head>
<body>
    <nav class="navbar navbar-custom navbar-expand-lg">
        <div class="container d-flex align-items-center justify-content-between">
            <a class="navbar-brand" href="/"><i class="fas fa-power-off"></i> GIZMO Store</a>
            <div class="d-flex gap-2 align-items-center flex-wrap">
                <a class="nav-link" href="/"> <i class="fas fa-home"></i> Home</a>
                <a class="nav-link" href="{{ route('admin.coupons.index') }}"> <i class="fas fa-arrow-left"></i> Back to Coupons</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h3><i class="fas fa-plus-circle"></i> Create Coupon</h3>

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <strong>Errors:</strong>
                <ul class="mb-0 ms-3">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('admin.coupons.store') }}" method="POST" class="row g-3">
            @csrf
            <div class="col-md-6">
                <label class="form-label"><i class="fas fa-code me-2" style="color: #DC143C;"></i>Code <span class="text-danger">*</span></label>
                <input type="text" name="code" class="form-control" value="{{ old('code') }}" placeholder="e.g. SAVE10" required>
            </div>

            <div class="col-md-6">
                <label class="form-label"><i class="fas fa-tags me-2" style="color: #DC143C;"></i>Type <span class="text-danger">*</span></label>
                <select name="type" class="form-select" required>
                    <option value="percent" {{ old('type') === 'percent' ? 'selected' : '' }}>Percent off</option>
                    <option value="fixed" {{ old('type') === 'fixed' ? 'selected' : '' }}>Fixed amount off</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label"><i class="fas fa-percentage me-2" style="color: #DC143C;"></i>Value <span class="text-danger">*</span></label>
                <input type="number" name="value" class="form-control" step="0.01" min="0" value="{{ old('value') }}" required>
                <div class="form-text">Percent (e.g. 10) or amount (e.g. 5.00)</div>
            </div>

            <div class="col-md-6">
                <label class="form-label"><i class="fas fa-money-bill-wave me-2" style="color: #DC143C;"></i>Min. purchase (optional)</label>
                <input type="number" name="min_purchase" class="form-control" step="0.01" min="0" value="{{ old('min_purchase') }}" placeholder="0">
            </div>

            <div class="col-md-6">
                <label class="form-label"><i class="fas fa-calendar-check me-2" style="color: #DC143C;"></i>Starts at</label>
                <input type="date" name="starts_at" class="form-control" value="{{ old('starts_at') }}">
            </div>

            <div class="col-md-6">
                <label class="form-label"><i class="fas fa-calendar-times me-2" style="color: #DC143C;"></i>Ends at</label>
                <input type="date" name="ends_at" class="form-control" value="{{ old('ends_at') }}">
            </div>

            <div class="col-md-6">
                <label class="form-label"><i class="fas fa-percentage me-2" style="color: #DC143C;"></i>Use limit (optional)</label>
                <input type="number" name="use_limit" class="form-control" min="1" value="{{ old('use_limit') }}" placeholder="Unlimited">
            </div>

            <div class="col-12 mt-3">
                <button type="submit" class="btn btn-warning"><i class="fas fa-save me-2"></i>Create Coupon</button>
                <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary ms-2"><i class="fas fa-times me-2"></i>Cancel</a>
            </div>
        </form>
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

        // Listen for changes from other tabs/windows
        window.addEventListener('storage', (e) => {
            if (e.key === STORAGE_KEY) {
                if (e.newValue === 'true') html.classList.add('dark-mode'); else html.classList.remove('dark-mode');
            }
        });
    </script>
</body>
</html>
