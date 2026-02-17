<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <title>Manage Products - KS Tech</title>
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
        }

        /* Navbar Styling */
        .navbar-custom {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
            border-bottom: 2px solid #DC143C;
            padding: 1rem 0;
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
            margin: 0 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
            border-radius: 6px;
            padding: 0.5rem 1rem !important;
        }

        .navbar-custom .nav-link:hover {
            color: #DC143C !important;
            background: rgba(220, 20, 60, 0.15);
        }

        .dropdown-menu {
            background: linear-gradient(135deg, #1e1e1e 0%, #2a2a2a 100%) !important;
            border: 1px solid rgba(220, 20, 60, 0.2) !important;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4) !important;
        }

        .dropdown-item {
            color: #e0e0e0 !important;
        }

        .dropdown-item:hover,
        .dropdown-item:focus {
            background: rgba(220, 20, 60, 0.15) !important;
            color: #DC143C !important;
        }

        .dropdown-divider {
            border-color: rgba(220, 20, 60, 0.2) !important;
        }

        /* Container */
        .admin-container {
            padding: 2rem 1rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, #1e1e1e 0%, #2a2a2a 100%);
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            border-left: 4px solid #DC143C;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .page-header h1 {
            font-size: 2.2rem;
            font-weight: 700;
            color: #ffffff;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            margin: 0;
        }

        .page-header h1 i {
            color: #DC143C;
        }

        /* Button Styling */
        .btn-add {
            background: linear-gradient(135deg, #DC143C 0%, #8B0000 100%);
            color: white;
            border: none;
            font-weight: 600;
            padding: 0.7rem 1.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-add:hover {
            color: white;
            background: linear-gradient(135deg, #8B0000 0%, #6B0000 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 153, 0, 0.3);
        }

        /* Product Grid */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        /* Product Card */
        .product-card {
            background: linear-gradient(135deg, #1e1e1e 0%, #2a2a2a 100%);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(255, 153, 0, 0.2);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(255, 153, 0, 0.2);
            border-color: rgba(255, 153, 0, 0.5);
        }

        .product-image {
            height: 220px;
            overflow: hidden;
            background: #2a2a2a;
            position: relative;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.3s ease;
        }

        .product-card:hover .product-image img {
            transform: scale(1.05);
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            filter: invert(1);
        }

        .product-body {
            padding: 1.5rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .product-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 0.8rem;
        }

        .product-description {
            color: #b0b0b0;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.4;
        }

        .product-info {
            margin-bottom: 1rem;
        }

        .product-info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .product-info-label {
            color: #b0b0b0;
        }

        .product-info-value {
            color: #DC143C;
            font-weight: 600;
        }

        .product-category {
            display: inline-block;
            background: rgba(220, 20, 60, 0.15);
            color: #DC143C;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 1rem;
        }

        .product-offer {
            background: rgba(76, 175, 80, 0.15);
            color: #00ff88;
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 1rem;
            display: inline-block;
        }

        .product-offer a {
            color: #00ff88;
            text-decoration: underline;
        }

        .product-footer {
            display: flex;
            gap: 0.8rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(255, 153, 0, 0.1);
            margin-top: auto;
        }

        .btn-edit {
            flex: 1;
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            border: none;
            padding: 0.6rem;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            font-size: 0.9rem;
        }

        .btn-edit:hover {
            background: linear-gradient(135deg, #2980b9 0%, #1f618d 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        }

        .btn-delete {
            flex: 1;
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
            border: none;
            padding: 0.6rem;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            font-size: 0.9rem;
        }

        .btn-delete:hover {
            background: linear-gradient(135deg, #c0392b 0%, #a93226 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #b0b0b0;
        }

        .empty-state i {
            font-size: 4rem;
            color: #DC143C;
            margin-bottom: 1rem;
            opacity: 0.8;
        }

        .empty-state h3 {
            color: #ffffff;
            margin-bottom: 1rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .page-header h1 {
                font-size: 1.8rem;
            }

            .products-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .btn-add {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 576px) {
            .admin-container {
                padding: 1rem;
            }

            .page-header {
                padding: 1.5rem;
            }

            .page-header h1 {
                font-size: 1.5rem;
            }

            .product-card {
                margin-bottom: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                <i class="fas fa-power-off"></i> GIZMO SD
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="/"><i class="fas fa-home"></i> Home</a>
                    </li>
                    @php use Illuminate\Support\Facades\Auth; @endphp
                    @if(Auth::check())
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-user"></i> Profile</a></li>
                                @if(Auth::user()->is_admin)
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('product.index') }}"><i class="fas fa-boxes"></i> Manage Products</a></li>
                                    <li><a class="dropdown-item" href="{{ route('category.index') }}"><i class="fas fa-list"></i> Manage Categories</a></li>
                                    <li><a class="dropdown-item" href="{{ route('users.index') }}"><i class="fas fa-users-cog"></i> Manage Users</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="dropdown-item" style="border: none; background: none; cursor: pointer;">
                                            <i class="fas fa-sign-out-alt"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> Sign In</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}"><i class="fas fa-user-plus"></i> Sign Up</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="admin-container">
        <div class="page-header">
            <h1>
                <i class="fas fa-boxes"></i>
                Manage Products
            </h1>
            <a href="{{ route('product.create') }}" class="btn-add"><i class="fas fa-plus"></i> Add Product</a>
        </div>

        @if($products->count() > 0)
            <div class="products-grid">
                @foreach($products as $product)
                    <div class="product-card">
                        <div class="product-image">
                            @if($product->images->count())
                                <div id="carouselProduct{{ $product->id }}" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                        @foreach($product->images as $img)
                                            <div class="carousel-item @if($loop->first) active @endif">
                                                <img src="{{ filter_var($img->image_path, FILTER_VALIDATE_URL) ? $img->image_path : asset($img->image_path) }}" alt="Product Image">
                                            </div>
                                        @endforeach
                                    </div>
                                    @if($product->images->count() > 1)
                                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselProduct{{ $product->id }}" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#carouselProduct{{ $product->id }}" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        </button>
                                    @endif
                                </div>
                            @else
                                <img src="https://via.placeholder.com/300x220?text=No+Image" alt="No Image">
                            @endif
                        </div>

                        <div class="product-body">
                            <h5 class="product-title">{{ $product->name }}</h5>
                            <p class="product-description">{{ $product->description }}</p>

                            @if($product->category)
                                <span class="product-category"><i class="fas fa-tag"></i> {{ $product->category->name }}</span>
                            @endif

                            <div class="product-info">
                                <div class="product-info-row">
                                    <span class="product-info-label">Price:</span>
                                    <span class="product-info-value">${{ number_format($product->price, 2) }}</span>
                                </div>
                                <div class="product-info-row">
                                    <span class="product-info-label">Stock:</span>
                                    <span class="product-info-value">{{ $product->quantity }} units</span>
                                </div>
                            </div>

                            @if($product->offer)
                                <div class="product-offer">
                                    <i class="fas fa-gift"></i> {{ $product->offer->offer_name ?? $product->offer->gift_name }}
                                    <a href="{{ route('offer.edit', $product->offer) }}"><small>[Edit]</small></a>
                                </div>
                            @endif

                            <div class="product-footer">
                                <a href="{{ route('product.edit', $product->id) }}" class="btn-edit"><i class="fas fa-edit"></i> Edit</a>
                                <form action="{{ route('product.destroy', $product->id) }}" method="POST" style="flex: 1;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete" onclick="return confirm('Are you sure you want to delete this product?');"><i class="fas fa-trash"></i> Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-box-open"></i>
                <h3>No Products Found</h3>
                <p>Start by adding a new product to your inventory.</p>
                <a href="{{ route('product.create') }}" class="btn-add" style="margin-top: 1rem;"><i class="fas fa-plus"></i> Add Your First Product</a>
            </div>
        @endif
    </div>
</body>
</html>
