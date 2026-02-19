<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Manage Categories - KS Tech</title>
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
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
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
            background: rgba(255, 153, 0, 0.1);
        }

        /* Main Container */
        .admin-container {
            padding: 2rem 1rem;
        }

        /* Header */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .section-header {
            background: linear-gradient(135deg, #1e1e1e 0%, #2a2a2a 100%);
            padding: 2rem;
            border-radius: 12px;
            border-left: 4px solid #DC143C;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .section-header h1 {
            font-size: 2.2rem;
            font-weight: 700;
            color: #ffffff;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .section-header h1 i {
            color: #DC143C;
        }

        /* Button Styling */
        .btn-primary-custom {
            background: linear-gradient(135deg, #DC143C 0%, #8B0000 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.7rem 1.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-primary-custom:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 153, 0, 0.3);
        }

        /* Table Card */
        .table-card {
            background: linear-gradient(135deg, #1e1e1e 0%, #2a2a2a 100%);
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
            overflow: hidden;
            border: 1px solid rgba(255, 153, 0, 0.2);
        }

        .table-card-header {
            background: linear-gradient(135deg, #DC143C 0%, #8B0000 100%);
            color: white;
            padding: 1.5rem;
            font-size: 1.1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .table {
            margin-bottom: 0;
            font-size: 0.95rem;
            color: #e0e0e0;
        }

        .table thead {
            background: rgba(30, 30, 30, 0.8);
            border-bottom: 2px solid rgba(220, 20, 60, 0.2);
        }

        .table thead th {
            color: #DC143C;
            font-weight: 600;
            padding: 1.2rem 1rem;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            vertical-align: middle;
        }

        .table tbody tr {
            border-bottom: 1px solid rgba(220, 20, 60, 0.1);
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: rgba(255, 153, 0, 0.05);
        }

        .table tbody td {
            padding: 1.2rem 1rem;
            vertical-align: middle;
            color: #e0e0e0;
        }

        .category-name {
            font-weight: 600;
            color: #000000;
        }

        .category-description {
            color: #333333;
            font-size: 0.9rem;
            max-width: 400px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            line-height: 1.4;
        }

        /* Action Buttons */
        .btn-action {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            margin-right: 0.5rem;
        }

        .btn-edit {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
        }

        .btn-edit:hover {
            background: linear-gradient(135deg, #2980b9 0%, #1f618d 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        }

        .btn-delete {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
        }

        .btn-delete:hover {
            background: linear-gradient(135deg, #c0392b 0%, #a93226 100%);
            transform: translateY(-2px);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #b0b0b0;
        }

        .empty-state i {
            font-size: 3rem;
            color: #DC143C;
            margin-bottom: 1rem;
        }

        /* Alert */
        .alert {
            border-radius: 10px;
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            background: linear-gradient(135deg, #2a2a2a 0%, #323232 100%);
            border: 1px solid rgba(0, 200, 83, 0.3);
        }

        .alert-success {
            background: linear-gradient(135deg, #2a2a2a 0%, #323232 100%);
            color: #00ff88;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .section-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .section-header h1 {
                font-size: 1.8rem;
            }

            .category-description {
                -webkit-line-clamp: 1;
            }

            .btn-action {
                padding: 0.4rem 0.8rem;
                font-size: 0.85rem;
                margin-right: 0.3rem;
            }

            .table {
                font-size: 0.85rem;
            }

            .table thead th,
            .table tbody td {
                padding: 0.8rem 0.5rem;
            }
        }

        @media (max-width: 576px) {
            .admin-container {
                padding: 1rem;
            }

            .section-header h1 {
                font-size: 1.5rem;
            }

            .btn-primary-custom {
                padding: 0.6rem 1rem;
                font-size: 0.9rem;
                width: 100%;
                justify-content: center;
            }

            .table {
                font-size: 0.75rem;
            }

            .table thead th,
            .table tbody td {
                padding: 0.5rem;
            }

            .category-description {
                display: none;
            }

            .btn-action {
                padding: 0.3rem 0.6rem;
                font-size: 0.75rem;
                margin-right: 0.2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-custom navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                <i class="fas fa-power-off"></i> GIZMO SD
            </a>
            <div class="d-flex gap-3 flex-wrap">
                <a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="fas fa-chart-line"></i> Dashboard</a>
                <a class="nav-link" href="{{ route('product.index') }}"><i class="fas fa-boxes"></i> Products</a>
                <a class="nav-link" href="{{ route('category.index') }}"><i class="fas fa-list"></i> Categories</a>
                <a class="nav-link" href="{{ route('users.index') }}"><i class="fas fa-users"></i> Users</a>
                <a class="nav-link" href="/">Back to Store</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid admin-container">
        <!-- Header -->
        <div class="section-header">
            <h1><i class="fas fa-list"></i> Manage Categories</h1>
            <a href="{{ route('category.create') }}" class="btn-primary-custom">
                <i class="fas fa-plus-circle"></i> Add New Category
            </a>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Categories Table -->
        <div class="table-card">
            <div class="table-card-header">
                <i class="fas fa-layer-group"></i> Categories List
                <span class="badge bg-white text-dark ms-auto">{{ count($categories) }} Categories</span>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 5%">#</th>
                            <th style="width: 25%">Category Name</th>
                            <th style="width: 50%">Description</th>
                            <th style="width: 20%; text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td><strong>{{ $category->id }}</strong></td>
                                <td><span class="category-name">{{ $category->name }}</span></td>
                                <td><span class="category-description">{{ $category->description }}</span></td>
                                <td style="text-align: center;">
                                    <a href="{{ route('category.edit', $category->id) }}" class="btn btn-action btn-edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('category.destroy', $category->id) }}" method="POST" style="display:inline-block;" class="confirmable-form" data-confirm="Are you sure you want to delete this category?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-action btn-delete">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <p>No categories yet. <a href="{{ route('category.create') }}" style="color: #DC143C;">Create one now!</a></p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>