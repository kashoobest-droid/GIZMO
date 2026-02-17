<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Contact Us - Gizmo Store</title>
    <style>
        body { 
            background: #f5f5f5;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
        
        .navbar-custom { 
            background: #1a1a1a;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .navbar-custom .navbar-brand { 
            color: #DC143C !important;
            font-size: 1.8rem;
            font-weight: bold;
        }
        
        .navbar-custom .nav-link { 
            color: #ffffff !important;
            margin: 0 10px;
            transition: color 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .navbar-custom .nav-link:hover {
            color: #DC143C !important;
        }
        
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 0;
            text-align: center;
        }

        .hero-section h1 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .contact-card { 
            max-width: 600px; 
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .contact-card .card-header {
            background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);
            color: white;
            padding: 30px;
            border-bottom: 4px solid #DC143C;
        }

        .contact-card .card-header h3 {
            margin: 0;
            font-weight: 700;
            color: #DC143C;
        }

        .contact-card .card-body {
            padding: 30px;
        }

        .form-label {
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 8px;
        }

        .form-control {
            border: 1px solid #ddd;
            border-radius: 6px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #DC143C;
            box-shadow: 0 0 0 0.2rem rgba(220, 20, 60, 0.25);
        }

        .btn-warning {
            background: #DC143C !important;
            border-color: #DC143C !important;
            color: white !important;
            font-weight: 600;
        }

        .btn-warning:hover {
            background: #8B0000 !important;
            border-color: #8B0000 !important;
        }

        .alert {
            border-radius: 8px;
            border: none;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        h2 {
            color: #1a1a1a;
            font-weight: 700;
        }

        .page-title {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #DC143C;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="/"><i class="fas fa-power-off"></i> Gizmo Store</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="navbar-nav ms-auto">
                    <a class="nav-link" href="/"><i class="fas fa-home"></i> Home</a>
                    <a class="nav-link" href="{{ route('faq') }}"><i class="fas fa-question-circle"></i> FAQ</a>
                    <a class="nav-link" href="{{ route('order.track.show') }}"><i class="fas fa-map-location-dot"></i> Track Order</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <h1>Get In Touch</h1>
            <p>We'd love to hear from you. Send us a message!</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container my-5">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        <div class="contact-card">
            <div class="card-header">
                <h3><i class="fas fa-envelope me-2"></i>Contact Us</h3>
            </div>
            <div class="card-body">
                <p class="text-muted mb-4">Have a question or feedback? Send us a message and we'll get back to you as soon as possible.</p>
                <form action="{{ route('contact.submit') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Name *</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message *</label>
                        <textarea name="message" class="form-control @error('message') is-invalid @enderror" rows="5" required>{{ old('message') }}</textarea>
                        @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-warning"><i class="fas fa-paper-plane me-2"></i>Send Message</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
