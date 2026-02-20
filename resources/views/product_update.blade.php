<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <title>Update Product - Gizmo Store</title>
    <style>
        body {
            background-color: #f5f5f5;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        /* Navbar Styling */
        .navbar-custom {
            background-color: #1a1a1a;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .navbar-custom .navbar-brand {
            font-size: 1.8rem;
            font-weight: bold;
            color: #DC143C !important;
            letter-spacing: 1px;
        }

        .navbar-custom .nav-link {
            color: #ffffff !important;
            margin: 0 10px;
            transition: color 0.3s;
        }

        .navbar-custom .nav-link:hover {
            color: #DC143C !important;
        }

        /* Hero Section */
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

        /* Form Card */
        .form-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .form-card .card-header {
            background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);
            color: white;
            padding: 30px;
            border-bottom: 4px solid #DC143C;
        }

        .form-card .card-header h3 {
            margin: 0;
            font-weight: 700;
            color: #DC143C;
        }

        .form-card .card-body {
            padding: 30px;
        }

        /* Form elements */
        .form-label {
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 8px;
        }

        .form-control, .form-select {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 10px 12px;
            transition: all 0.3s;
        }

        .form-control:focus, .form-select:focus {
            border-color: #DC143C;
            box-shadow: 0 0 0 0.2rem rgba(220, 20, 60, 0.25);
        }

        /* Buttons */
        .btn-warning {
            background: #DC143C !important;
            border-color: #DC143C !important;
            color: white !important;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-warning:hover {
            background: #8B0000 !important;
            border-color: #8B0000 !important;
        }

        .btn-secondary {
            background: #666 !important;
            border-color: #666 !important;
            font-weight: 600;
        }

        .btn-secondary:hover {
            background: #444 !important;
        }

        /* Alerts */
        .alert-danger {
            background-color: #f8d7da;
            border-color: #DC143C;
            color: #721c24;
        }

        /* Image preview area */
        #dropZone {
            background: #f9f9f9 !important;
            border: 2px dashed #DC143C !important;
            transition: all 0.3s;
            cursor: pointer;
        }

        #dropZone:hover {
            background: #f0f0f0 !important;
        }

        #dropZone.border-info {
            border-color: #DC143C !important;
            background: rgba(220, 20, 60, 0.05) !important;
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            filter: brightness(0) invert(1);
        }

        /* Footer */
        .footer-custom {
            background: #1a1a1a;
            color: white;
            padding: 40px 0 20px;
            margin-top: 60px;
        }

        .footer-custom a {
            color: #DC143C;
        }

        .footer-custom a:hover {
            color: #FF8A8A;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                <i class="fas fa-power-off"></i> Gizmo Store
            </a>
            <span class="navbar-text text-white ms-2">/ Update Product</span>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <h1>Update Product</h1>
            <p>Modify product details and images</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-900 col-md-10">
                <div class="form-card">
                    <div class="card-header">
                        <h3><i class="fas fa-edit me-2"></i>Edit Product</h3>
                    </div>
                    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h4 class="alert-heading">Validation Errors!</h4>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label for="name" class="form-label">Product Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $product->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price (SDG)</label>
                <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $product->price) }}" required>
                @error('price')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ old('quantity', $product->quantity) }}" required>
                @error('quantity')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="category_id" class="form-label">Category</label>
                <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                    <option value="">Select a category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $product->Category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Current Images</label>
                <div id="updateImageCarousel" class="carousel slide mb-2" data-bs-ride="carousel" style="max-width:350px;">
                    <div class="carousel-inner" id="updateCarouselInner">
                        @foreach($product->images as $img)
                            <div class="carousel-item @if($loop->first) active @endif">
                                <img src="{{ filter_var($img->image_path, FILTER_VALIDATE_URL) ? $img->image_path : asset($img->image_path) }}" class="d-block w-100" style="height:220px;object-fit:cover;" alt="Product Image">
                            </div>
                        @endforeach
                    </div>
                    @if($product->images->count() > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#updateImageCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#updateImageCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    @endif
                </div>
                <div class="d-flex flex-wrap gap-2 mb-3">
                    @foreach($product->images as $image)
                        <div class="position-relative" style="display:inline-block;">
                            <img src="{{ filter_var($image->image_path, FILTER_VALIDATE_URL) ? $image->image_path : asset($image->image_path) }}" alt="Product Image" style="width: 100px; height: 100px; object-fit: cover;">
                            <div class="mt-1">
                                <input type="checkbox" name="remove_images[]" value="{{ $image->id }}"> Remove
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Add New Images (max 6 total)</label>
                <div id="dropZone" class="border border-primary rounded p-3 mb-2 text-center bg-light @error('images') border-danger @enderror" style="cursor:pointer;">
                    <span id="dropZoneText">Drag & drop images here or click to select (max 6)</span>
                    <input type="file" class="form-control d-none" name="images[]" accept="image/*" multiple id="imageInput">
                </div>
                <small class="form-text text-muted">You can select up to 6 images. Hold Ctrl (Windows/Linux) or Cmd (Mac) to select multiple files.</small>
                <div id="imagePreviewCarousel" class="carousel slide mt-3" data-bs-ride="carousel" style="display:none;max-width:350px;">
                    <div class="carousel-inner" id="carouselInner"></div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#imagePreviewCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#imagePreviewCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
                @error('images')
                    <div class="text-danger small mt-2">{{ $message }}</div>
                @enderror
            </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-warning btn-lg">
                            <i class="fas fa-save me-2"></i>Update Product
                        </button>
                        <a href="{{ route('product.index') }}" class="btn btn-secondary btn-lg">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    </div>
                </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer-custom">
        <div class="container">
            <div class="text-center">
                <p>&copy; 2024 Gizmo Store. All rights reserved.</p>
            </div>
        </div>
    </footer>

<script>
// Drag & drop and image preview carousel logic for update form
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
                    div.innerHTML = `<img src="${ev.target.result}" class="d-block w-100" style="height:220px;object-fit:cover;" alt="Preview New Image ${idx + 1}">`;
                    carouselInner.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
            carousel.style.display = 'block';
            dropZoneText.textContent = `Selected ${files.length} new image${files.length > 1 ? 's' : ''}`;
        } else {
            carousel.style.display = 'none';
            dropZoneText.textContent = 'Drag & drop images here or click to select (max 6)';
        }
    }

    dropZone.addEventListener('click', () => imageInput.click());

    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
        dropZone.classList.add('border-info', 'bg-info', 'bg-opacity-10');
        dropZoneText.textContent = 'Drop images here';
    });

    dropZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        e.stopPropagation();
        dropZone.classList.remove('border-info', 'bg-info', 'bg-opacity-10');
        if (imageInput.files.length === 0) {
            dropZoneText.textContent = 'Drag & drop images here or click to select (max 6)';
        }
    });

    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        dropZone.classList.remove('border-info', 'bg-info', 'bg-opacity-10');
        
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