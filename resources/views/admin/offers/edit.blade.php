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
    <title>Edit Offer - GIZMO Store</title>
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
            margin-bottom: 1.5rem;
        }

        .form-label {
            color: var(--text-primary);
            font-weight: 500;
        }

        .form-control, .form-select, textarea {
            background-color: var(--bg-secondary);
            color: var(--text-primary);
            border-color: var(--border-color);
        }

        .form-control:focus, .form-select:focus, textarea:focus {
            background-color: var(--bg-secondary);
            color: var(--text-primary);
            border-color: #DC143C;
            box-shadow: 0 0 0 0.2rem rgba(220, 20, 60, 0.25);
        }

        .form-text {
            color: var(--text-secondary);
        }

        .btn-warning {
            background: linear-gradient(135deg, #DC143C 0%, #8B0000 100%);
            border: none;
            color: white;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-warning:hover {
            background: linear-gradient(135deg, #8B0000 0%, #5c0000 100%);
        }

        .btn-secondary {
            background-color: var(--bg-secondary);
            color: var(--text-primary);
            border-color: var(--border-color);
        }

        .btn-secondary:hover {
            background-color: #DC143C;
            color: white;
            border-color: #DC143C;
        }

        .alert {
            background-color: var(--bg-secondary);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        .alert-danger {
            background-color: rgba(220, 20, 60, 0.1);
            border-color: #DC143C;
        }

        .text-danger {
            color: #DC143C !important;
        }

        .image-preview {
            max-width: 150px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            padding: 8px;
            margin-top: 10px;
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
                <a class="nav-link" href="{{ route('offer.index') }}">
                    <i class="fas fa-arrow-left"></i> Back to Offers
                </a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h3><i class="fas fa-edit"></i> Edit Offer</h3>

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

        <form action="{{ route('offer.update', $offer) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label"><i class="fas fa-box me-2" style="color: #DC143C;"></i>Product <span class="text-danger">*</span></label>
                <select name="product_id" class="form-select" required>
                    <option value="">-- Select a product (offer will show on this product's card) --</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}" {{ old('product_id', $offer->product_id) == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
                <div class="form-text">The offer appears on the home page and product page only for this product.</div>
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="fas fa-tag me-2" style="color: #DC143C;"></i>Offer Name</label>
                <input name="offer_name" class="form-control" value="{{ old('offer_name', $offer->offer_name) }}" placeholder="e.g., Special Bundle Offer">
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="fas fa-gift me-2" style="color: #DC143C;"></i>Gift Name <span class="text-danger">*</span></label>
                <input name="gift_name" class="form-control" value="{{ old('gift_name', $offer->gift_name) }}" placeholder="e.g., Free Shipping" required>
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="fas fa-file-alt me-2" style="color: #DC143C;"></i>Description</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Describe the offer details...">{{ old('description', $offer->description) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="fas fa-image me-2" style="color: #DC143C;"></i>Image</label>
                <input type="file" name="image" class="form-control" accept="image/*">
                <div class="form-text">Upload a new image to replace the current one (optional)</div>
                @if($offer->image_path)
                    <div class="mt-3">
                        <label class="form-label" style="font-size: 0.9rem;">Current Image:</label>
                        <img src="{{ filter_var($offer->image_path, FILTER_VALIDATE_URL) ? $offer->image_path : asset($offer->image_path) }}" class="image-preview" alt="Offer Image">
                    </div>
                @endif
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label"><i class="fas fa-calendar-check me-2" style="color: #DC143C;"></i>Starts At</label>
                    <input type="datetime-local" name="starts_at" value="{{ old('starts_at', optional($offer->starts_at)->format('Y-m-d\TH:i')) }}" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label"><i class="fas fa-calendar-times me-2" style="color: #DC143C;"></i>Ends At</label>
                    <input type="datetime-local" name="ends_at" value="{{ old('ends_at', optional($offer->ends_at)->format('Y-m-d\TH:i')) }}" class="form-control">
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-save me-2"></i> Save Changes
                </button>
                <a href="{{ route('offer.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i> Cancel
                </a>
            </div>
        </form>
    </div>

    <!-- Dark Mode Initialization Script -->
    <script>
        const STORAGE_KEY = 'gizmo-store-dark-mode';
        const html = document.documentElement;

        function initializeDarkMode() {
            const isDarkMode = localStorage.getItem(STORAGE_KEY) === 'true';
            if (isDarkMode) {
                html.classList.add('dark-mode');
            } else {
                html.classList.remove('dark-mode');
            }
        }

        initializeDarkMode();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
