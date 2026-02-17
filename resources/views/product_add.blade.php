<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <title>Add Product - KS Tech</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #0f0f0f 0%, #1a1a1a 100%);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            color: #e0e0e0;
            min-height: 100vh;
            padding: 2rem 1rem;
        }

        .navbar-custom {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
            border-bottom: 2px solid #DC143C;
            padding: 1rem 0;
            margin-bottom: 2rem;
        }

        .navbar-custom .navbar-brand {
            color: #DC143C !important;
            font-weight: 700;
            font-size: 1.4rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .navbar-custom .nav-link {
            color: #e0e0e0 !important;
            font-weight: 500;
            transition: all 0.3s ease;
            border-radius: 6px;
            padding: 0.5rem 1rem !important;
            margin: 0 0.3rem;
        }

        .navbar-custom .nav-link:hover {
            color: #DC143C !important;
            background: rgba(220, 20, 60, 0.15);
        }

        .page-header {
            margin-bottom: 2rem;
            text-align: center;
            padding-bottom: 2rem;
            border-bottom: 2px solid rgba(220, 20, 60, 0.3);
        }

        .page-header h1 {
            color: #ffffff;
            font-size: 2.2rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.8rem;
        }

        .page-header h1 i {
            color: #DC143C;
        }

        .product-form-container {
            max-width: 900px;
            margin: 0 auto;
            background: linear-gradient(135deg, #1e1e1e 0%, #2a2a2a 100%);
            border-radius: 12px;
            padding: 2.5rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(220, 20, 60, 0.2);
        }

        .form-section {
            margin-bottom: 2rem;
        }

        .form-section h5 {
            color: #DC143C;
            font-weight: 700;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid rgba(220, 20, 60, 0.3);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-label {
            color: #e0e0e0;
            font-weight: 600;
            margin-bottom: 0.7rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-label i {
            color: #DC143C;
            font-size: 0.9rem;
        }

        .form-control {
            background: #2a2a2a;
            border: 1px solid rgba(220, 20, 60, 0.3);
            color: #e0e0e0;
            border-radius: 8px;
            padding: 0.8rem 1rem;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .form-control:focus {
            background: #323232;
            border-color: #DC143C;
            color: #e0e0e0;
            box-shadow: 0 0 0 0.2rem rgba(220, 20, 60, 0.25);
        }

        .form-control::placeholder {
            color: #7f8c8d;
        }

        .form-select {
            background: #2a2a2a url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23e0e0e0' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") right 0.75rem center/16px 16px no-repeat;
            border: 1px solid rgba(220, 20, 60, 0.3);
            color: #e0e0e0;
            border-radius: 8px;
            padding: 0.8rem 2.5rem 0.8rem 1rem;
            transition: all 0.3s ease;
        }

        .form-select:focus {
            background-color: #323232;
            border-color: #DC143C;
            color: #e0e0e0;
            box-shadow: 0 0 0 0.2rem rgba(220, 20, 60, 0.25);
        }

        .form-select option {
            background: #2a2a2a;
            color: #e0e0e0;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        .form-text {
            color: #b0b0b0;
            font-size: 0.85rem;
            margin-top: 0.5rem;
        }

        .invalid-feedback {
            color: #ff6b6b;
            font-size: 0.85rem;
            display: block;
            margin-top: 0.5rem;
        }

        .form-control.is-invalid {
            border-color: #ff6b6b;
        }

        .form-control.is-invalid:focus {
            border-color: #ff6b6b;
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 107, 0.25);
        }

        /* Drop Zone */
        .drop-zone {
            border: 2px dashed rgba(220, 20, 60, 0.5);
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            background: rgba(255, 153, 0, 0.05);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .drop-zone:hover {
            border-color: #DC143C;
            background: rgba(220, 20, 60, 0.1);
        }

        .drop-zone.dragover {
            border-color: #DC143C;
            background: rgba(220, 20, 60, 0.15);
        }

        .drop-zone-text {
            color: #e0e0e0;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .carousel {
            background: #2a2a2a;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid rgba(220, 20, 60, 0.3);
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            filter: invert(1);
        }

        /* Buttons */
        .btn-submit {
            background: linear-gradient(135deg, #DC143C 0%, #8B0000 100%);
            color: white;
            border: none;
            padding: 0.9rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-submit:hover {
            color: white;
            background: linear-gradient(135deg, #8B0000 0%, #6B0000 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(220, 20, 60, 0.3);
        }

        .btn-cancel {
            background: #2d2d2d;
            color: #e0e0e0;
            border: 1px solid rgba(220, 20, 60, 0.3);
            padding: 0.9rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-cancel:hover {
            color: #e0e0e0;
            background: #3a3a3a;
            border-color: #DC143C;
        }

        .button-group {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-top: 2rem;
        }

        /* Alert */
        .alert {
            background: linear-gradient(135deg, #2a2a2a 0%, #323232 100%);
            border: 1px solid rgba(255, 107, 107, 0.3);
            color: #ff9999;
            border-radius: 8px;
            margin-bottom: 2rem;
        }

        .alert-heading {
            color: #ff6b6b;
            font-weight: 700;
        }

        .alert ul {
            margin-bottom: 0;
        }

        .alert-dismissible .btn-close {
            filter: invert(1) brightness(0.8);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 1.8rem;
            }

            .product-form-container {
                padding: 1.5rem;
            }

            .button-group {
                flex-direction: column;
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
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                <i class="fas fa-power-off"></i>
                GIZMO SD Admin
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/"><i class="fas fa-home"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('product.index') }}"><i class="fas fa-boxes"></i> Products</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="page-header">
        <h1>
            <i class="fas fa-plus-circle"></i>
            Add New Product
        </h1>
    </div>

    <div class="container-fluid">
        <div class="product-form-container">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h4 class="alert-heading"><i class="fas fa-exclamation-circle"></i> Validation Errors!</h4>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Basic Information Section -->
                <div class="form-section">
                    <h5><i class="fas fa-info-circle"></i> Basic Information</h5>

                    <div class="mb-3">
                        <label for="name" class="form-label"><i class="fas fa-cube"></i> Product Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Enter product name" required value="{{ old('name') }}">
                        <small class="form-text">Enter a clear, descriptive product name</small>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label"><i class="fas fa-align-left"></i> Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" placeholder="Describe your product in detail...">{{ old('description') }}</textarea>
                        <small class="form-text">Provide detailed information about features and benefits</small>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Pricing & Inventory Section -->
                <div class="form-section">
                    <h5><i class="fas fa-dollar-sign"></i> Pricing & Inventory</h5>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price" class="form-label"><i class="fas fa-price-tag"></i> Price (USD)</label>
                                <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" placeholder="0.00" required value="{{ old('price') }}">
                                <small class="form-text">Enter the selling price</small>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="quantity" class="form-label"><i class="fas fa-warehouse"></i> Stock Quantity</label>
                                <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" placeholder="0" required value="{{ old('quantity') }}">
                                <small class="form-text">Available units</small>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Category Section -->
                <div class="form-section">
                    <h5><i class="fas fa-tag"></i> Category</h5>

                    <div class="mb-3">
                        <label for="category_id" class="form-label"><i class="fas fa-list"></i> Select Category</label>
                        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                            <option value="">-- Select a category --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <small class="form-text">Choose the appropriate product category</small>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Product Images Section -->
                <div class="form-section">
                    <h5><i class="fas fa-images"></i> Product Images</h5>

                    <div class="mb-3">
                        <label class="form-label">Upload Images (1-6)</label>
                        <div id="dropZone" class="drop-zone @error('images') border-danger @enderror">
                            <i class="fas fa-cloud-upload-alt" style="font-size: 2.5rem; color: #DC143C; display: block; margin-bottom: 0.5rem;"></i>
                            <div class="drop-zone-text" id="dropZoneText">Drag & drop images here or click to select</div>
                            <small class="form-text">Supports JPG, PNG, GIF up to 6 images</small>
                            <input type="file" class="form-control d-none" name="images[]" accept="image/*" multiple id="imageInput">
                        </div>
                        <small class="form-text">Hold Ctrl (Windows) or Cmd (Mac) to select multiple files</small>

                        <div id="imagePreviewCarousel" class="carousel slide mt-3" data-bs-ride="carousel" style="display:none; max-width:400px;">
                            <div class="carousel-inner" id="carouselInner"></div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#imagePreviewCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#imagePreviewCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            </button>
                        </div>
                        @error('images')
                            <div class="text-danger small mt-2"><i class="fas fa-times-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="image_urls" class="form-label"><i class="fas fa-link"></i> Or Paste Image URLs</label>
                        <textarea class="form-control @error('image_urls') is-invalid @enderror" id="image_urls" name="image_urls" rows="3" placeholder="https://example.com/image1.jpg&#10;https://example.com/image2.jpg">{{ old('image_urls') }}</textarea>
                        <small class="form-text">Paste direct image URLs, one per line</small>
                        @error('image_urls')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="button-group">
                    <button type="submit" class="btn-submit"><i class="fas fa-plus"></i> Add Product</button>
                    <a href="{{ route('product.index') }}" class="btn-cancel"><i class="fas fa-times"></i> Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Drag & drop and image preview carousel logic
        document.addEventListener('DOMContentLoaded', function() {
            const imageInput = document.getElementById('imageInput');
            const dropZone = document.getElementById('dropZone');
            const dropZoneText = document.getElementById('dropZoneText');
            const carousel = document.getElementById('imagePreviewCarousel');
            const carouselInner = document.getElementById('carouselInner');

            function updatePreview(inputElement) {
                const files = Array.from(inputElement.files).slice(0, 6);
                carouselInner.innerHTML = '';

                if (files.length > 0) {
                    files.forEach((file, idx) => {
                        const reader = new FileReader();
                        reader.onload = function(ev) {
                            const div = document.createElement('div');
                            div.className = 'carousel-item' + (idx === 0 ? ' active' : '');
                            div.innerHTML = `<img src="${ev.target.result}" class="d-block w-100" style="height:300px;object-fit:cover;" alt="Preview ${idx + 1}">`;
                            carouselInner.appendChild(div);
                        };
                        reader.readAsDataURL(file);
                    });
                    carousel.style.display = 'block';
                    dropZoneText.textContent = `Selected ${files.length} image${files.length > 1 ? 's' : ''}`;
                } else {
                    carousel.style.display = 'none';
                    dropZoneText.textContent = 'Drag & drop images here or click to select';
                }
            }

            dropZone.addEventListener('click', () => imageInput.click());

            dropZone.addEventListener('dragover', function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropZone.classList.add('dragover');
                dropZoneText.textContent = 'Drop images here';
            });

            dropZone.addEventListener('dragleave', function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropZone.classList.remove('dragover');
                if (imageInput.files.length === 0) {
                    dropZoneText.textContent = 'Drag & drop images here or click to select';
                }
            });

            dropZone.addEventListener('drop', function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropZone.classList.remove('dragover');

                const droppedFiles = e.dataTransfer.files;
                if (droppedFiles.length > 0) {
                    imageInput.files = droppedFiles;
                    updatePreview(imageInput);
                }
            });

            imageInput.addEventListener('change', function(e) {
                updatePreview(imageInput);
            });
        });
    </script>
</body>
</html>
