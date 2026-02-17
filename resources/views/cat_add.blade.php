<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3H7lsmyQUcc4ohehQVv5/MI3GoKVXlaEzhv3HqVgRp2hFG12V0HIkWKpMlmLzn+F/PmZjMy5ivw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <title>Add Category</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #0f0f0f 0%, #1a1a1a 100%);
            color: #e0e0e0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            min-height: 100vh;
            padding: 40px 20px;
        }

        .navbar-dark {
            background: linear-gradient(90deg, #1a1a1a 0%, #2a2a2a 100%);
            box-shadow: 0 8px 32px rgba(255, 153, 0, 0.1);
            border-bottom: 2px solid #DC143C;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.3rem;
            background: linear-gradient(120deg, #DC143C, #FF6B6B);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-link {
            color: #b0b0b0 !important;
            transition: all 0.3s ease;
            margin: 0 8px;
        }

        .nav-link:hover {
            color: #DC143C !important;
        }

        .page-header {
            margin-bottom: 40px;
        }

        .page-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #e0e0e0;
            margin-bottom: 10px;
        }

        .page-header p {
            color: #b0b0b0;
            font-size: 1rem;
        }

        .form-container {
            max-width: 600px;
            margin: 0 auto;
            background: linear-gradient(135deg, #1e1e1e 0%, #2a2a2a 100%);
            border: 2px solid #DC143C;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 12px 48px rgba(255, 153, 0, 0.15);
        }

        .form-section {
            margin-bottom: 35px;
        }

        .form-section h4 {
            color: #DC143C;
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-label {
            color: #e0e0e0;
            font-weight: 500;
            margin-bottom: 10px;
            font-size: 0.95rem;
        }

        .form-control {
            background-color: #1a1a1a;
            border: 2px solid #333333;
            color: #e0e0e0;
            padding: 12px 16px;
            font-size: 0.95rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .form-control::placeholder {
            color: #666666;
        }

        .form-control:focus {
            background-color: #1a1a1a;
            border-color: #DC143C;
            color: #e0e0e0;
            box-shadow: 0 0 0 3px rgba(255, 153, 0, 0.1);
        }

        .form-control:hover:not(:focus) {
            border-color: #DC143C;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-buttons {
            display: flex;
            gap: 15px;
            margin-top: 40px;
        }

        .btn-submit {
            flex: 1;
            padding: 14px 24px;
            background: linear-gradient(135deg, #DC143C 0%, #8B0000 100%);
            color: #000000;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(255, 153, 0, 0.3);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .btn-cancel {
            flex: 1;
            padding: 14px 24px;
            background-color: #333333;
            color: #e0e0e0;
            border: 2px solid #555555;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-cancel:hover {
            background-color: #444444;
            border-color: #DC143C;
            color: #DC143C;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(255, 153, 0, 0.2);
        }

        .required {
            color: #ff6b6b;
        }

        @media (max-width: 768px) {
            .form-container {
                padding: 25px;
            }

            .page-header h1 {
                font-size: 2rem;
            }

            .form-buttons {
                flex-direction: column;
            }

            .btn-submit, .btn-cancel {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-dark navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                <i class="fas fa-store"></i> GIZMO SD
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="adminMenu" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-cog"></i> Admin
                        </a>
                        <ul class="dropdown-menu" style="background-color: #1a1a1a; border-color: #DC143C;">
                            <li><a class="dropdown-item" href="{{ route('product.index') }}" style="color: #e0e0e0;">Manage Products</a></li>
                            <li><a class="dropdown-item" href="{{ route('category.index') }}" style="color: #e0e0e0;">Manage Categories</a></li>
                            <li><a class="dropdown-item" href="{{ route('users.index') }}" style="color: #e0e0e0;">Manage Users</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h1><i class="fas fa-folder-plus"></i> Add New Category</h1>
            <p>Create a new product category for your store</p>
        </div>

        <!-- Form Container -->
        <div class="form-container">
            <form action="{{ route('category.store') }}" method="POST">
                @csrf

                <!-- Basic Information Section -->
                <div class="form-section">
                    <h4><i class="fas fa-info-circle"></i> Basic Information</h4>
                    
                    <div class="form-group">
                        <label for="name" class="form-label">Category Name <span class="required">*</span></label>
                        <input 
                            type="text" 
                            class="form-control @error('name') is-invalid @enderror" 
                            id="name" 
                            name="name"
                            placeholder="Enter category name (e.g., Laptops, Smartphones)"
                            value="{{ old('name') }}"
                            required
                        >
                        @error('name')
                            <div class="invalid-feedback" style="color: #ff6b6b;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label">Description <span class="required">*</span></label>
                        <textarea 
                            class="form-control @error('description') is-invalid @enderror" 
                            id="description" 
                            name="description"
                            rows="5"
                            placeholder="Write a detailed description of this category..."
                            required
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback" style="color: #ff6b6b;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="form-buttons">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-check"></i> Create Category
                    </button>
                    <a href="{{ route('category.index') }}" class="btn-cancel">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Add some interactive feedback
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'scale(1.02)';
                });
                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'scale(1)';
                });
            });
        });
    </script>
</body>
</html>