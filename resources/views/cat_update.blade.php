<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Edit Category - Gizmo SD</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            color: #2c3e50;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        /* Form Card */
        .form-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 700px;
            width: 100%;
            animation: slideUp 0.4s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Header */
        .form-header {
            background: linear-gradient(135deg, #DC143C 0%, #8B0000 100%);
            color: white;
            padding: 2.5rem;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1rem;
        }

        .form-header i {
            font-size: 3rem;
            opacity: 0.9;
        }

        .form-header h1 {
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0;
        }

        .form-header p {
            font-size: 0.95rem;
            opacity: 0.9;
            margin: 0;
        }

        /* Form Body */
        .form-body {
            padding: 2.5rem;
        }

        /* Form Group */
        .form-group {
            margin-bottom: 1.8rem;
        }

        .form-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.7rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.95rem;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 0.8rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #DC143C;
            box-shadow: 0 0 0 0.2rem rgba(220, 20, 60, 0.15);
            outline: none;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 140px;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        /* Form Buttons */
        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .btn-submit {
            background: linear-gradient(135deg, #DC143C 0%, #8B0000 100%);
            color: white;
            border: none;
            padding: 0.8rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.95rem;
        }

        .btn-submit:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 153, 0, 0.3);
        }

        .btn-cancel {
            background: #ecf0f1;
            color: #2c3e50;
            border: none;
            padding: 0.8rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            font-size: 0.95rem;
        }

        .btn-cancel:hover {
            background: #bdc3c7;
            color: white;
            transform: translateY(-2px);
        }

        /* Error Messages */
        .invalid-feedback {
            display: block;
            color: #e74c3c;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            font-weight: 500;
        }

        .alert-danger {
            background-color: #fadbd8;
            color: #c0392b;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid #e74c3c;
        }

        .alert-danger ul {
            margin: 0;
            padding-left: 1.5rem;
        }

        .alert-danger li {
            margin-bottom: 0.5rem;
        }

        /* Help Text */
        .form-text {
            font-size: 0.85rem;
            color: #7f8c8d;
            margin-top: 0.4rem;
        }

        /* Responsive */
        @media (max-width: 576px) {
            body {
                padding: 1rem;
            }

            .form-card {
                border-radius: 10px;
            }

            .form-header {
                padding: 1.5rem;
            }

            .form-header h1 {
                font-size: 1.5rem;
            }

            .form-header i {
                font-size: 2.5rem;
            }

            .form-body {
                padding: 1.5rem;
            }

            .form-actions {
                flex-direction: column;
                gap: 0.8rem;
            }

            .btn-submit,
            .btn-cancel {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="form-card">
        <!-- Header -->
        <div class="form-header">
            <i class="fas fa-edit"></i>
            <h1>Edit Category</h1>
            <p>Update category information</p>
        </div>

        <!-- Form Body -->
        <div class="form-body">
            <!-- Error Messages -->
            @if($errors->any())
                <div class="alert-danger">
                    <strong><i class="fas fa-exclamation-circle"></i> Please fix the following errors:</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('category.update', $category->id) }}" method="POST" novalidate>
                @csrf
                @method('PUT')

                <!-- Category Name -->
                <div class="form-group">
                    <label for="name" class="form-label">
                        <i class="fas fa-tag" style="color: #DC143C;"></i> Category Name
                    </label>
                    <input 
                        type="text" 
                        class="form-control @error('name') is-invalid @enderror" 
                        id="name" 
                        name="name" 
                        value="{{ old('name', $category->name) }}" 
                        placeholder="Enter category name (e.g., Electronics, Laptops)"
                        required
                    >
                    <div class="form-text">
                        <i class="fas fa-info-circle"></i> Give your category a clear, descriptive name
                    </div>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Category Description -->
                <div class="form-group">
                    <label for="description" class="form-label">
                        <i class="fas fa-align-left" style="color: #DC143C;"></i> Description
                    </label>
                    <textarea 
                        class="form-control @error('description') is-invalid @enderror" 
                        id="description" 
                        name="description" 
                        placeholder="Enter a detailed description of this category..."
                        required
                    >{{ old('description', $category->description) }}</textarea>
                    <div class="form-text">
                        <i class="fas fa-info-circle"></i> Provide a detailed description to help users understand this category
                    </div>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="form-actions">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> Update Category
                    </button>
                    <a href="{{ route('category.index') }}" class="btn-cancel">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>