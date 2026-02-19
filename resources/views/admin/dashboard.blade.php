<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="https://res.cloudinary.com/dgrnbtgts/image/upload/v1771338287/gizmo_qsab1d.png">
    <link rel="shortcut icon" href="https://res.cloudinary.com/dgrnbtgts/image/upload/v1771338287/gizmo_qsab1d.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <title>Admin Dashboard - GIZMO Store</title>
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

        /* Main Container */
        .admin-container {
            padding: 2rem 1rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Header */
        .admin-header {
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .admin-header h1 {
            font-size: 2.2rem;
            font-weight: 700;
            color: #ffffff;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .admin-header h1 i {
            color: #DC143C;
        }

        /* Stat Cards */
        .stat-card {
            background: linear-gradient(135deg, #1e1e1e 0%, #2a2a2a 100%);
            border-radius: 12px;
            padding: 1.8rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-left: 4px solid #DC143C;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(220, 20, 60, 0.2) 0%, transparent 70%);
            border-radius: 50%;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(220, 20, 60, 0.2);
        }

        .stat-card-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: #DC143C;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
        }

        .stat-card-label {
            color: #b0b0b0;
            font-size: 0.95rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            position: relative;
            z-index: 1;
        }

        .stat-card-label i {
            font-size: 1.2rem;
            opacity: 0.8;
            color: #DC143C;
        }

        /* Table Card */
        .table-card {
            background: linear-gradient(135deg, #1e1e1e 0%, #2a2a2a 100%);
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
            overflow: hidden;
            margin-top: 2rem;
            border: 1px solid rgba(220, 20, 60, 0.2);
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

        .table-responsive {
            border-collapse: collapse;
            overflow-x: auto;
        }
        /* Modern orders table */
        .table {
            margin-bottom: 0;
            font-size: 0.95rem;
            color: #e0e0e0;
            border-collapse: separate;
            border-spacing: 0 0.6rem; /* add vertical spacing between rows */
        }

        .table thead {
            background: transparent;
        }

        /* Ensure the header row itself is dark so the full row shows a solid dark bar */
        .table thead tr {
            background: linear-gradient(90deg, #0f0f0f 0%, #161616 100%);
            border-bottom: 1px solid rgba(255,255,255,0.04);
            border-radius: 10px 10px 0 0;
            overflow: hidden;
        }

        .table thead th {
            background: transparent; /* let the tr background show through */
            color: #f1f1f1;
            font-weight: 700;
            padding: 1rem 1rem;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.6px;
        }

        .table tbody tr {
            background: linear-gradient(120deg, #0f0f0f 0%, #161616 100%);
            box-shadow: 0 6px 18px rgba(0,0,0,0.6);
            transition: transform 0.18s ease, box-shadow 0.18s ease;
            border-radius: 10px;
            border: 1px solid rgba(255,255,255,0.03);
        }

        .table tbody tr:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(0,0,0,0.6);
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            color: #e8e8e8;
            background: transparent;
            border: none;
        }

        /* Make rounded rows show by hiding overflow on table wrapper */
        .table-responsive { overflow: visible; }

        /* Compact header for small screens */
        @media (max-width: 768px) {
            .table thead { display: none; }
            .table tbody tr { display: block; padding: 0.8rem; }
            .table tbody td { display: flex; justify-content: space-between; padding: 0.6rem 0.8rem; }
            .table tbody td:first-child { font-weight: 700; }
        }

        .badge {
            padding: 0.5rem 0.8rem;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.85rem;
        }

        .badge-pending {
            background: #DC143C;
            color: #ffffff;
        }

        .badge-processing {
            background: #0066cc;
            color: #ffffff;
        }

        .badge-shipped {
            background: #00ccff;
            color: #ffffff;
        }

        .badge-delivered {
            background: #00a86b;
            color: #ffffff;
        }

        .badge-cancelled {
            background: #ff4444;
            color: #ffffff;
        }

        /* Buttons */
        .btn-warning {
            background: linear-gradient(135deg, #DC143C 0%, #8B0000 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.6rem 1.2rem;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .btn-warning:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(220, 20, 60, 0.4);
        }

        .btn-outline-secondary {
            color: #b0b0b0;
            border-color: rgba(255, 153, 0, 0.3);
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-outline-secondary:hover {
            color: white;
            background-color: #7f8c8d;
            border-color: #7f8c8d;
        }

        /* Alert Styling */
        .alert {
            border-radius: 10px;
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-warning {
            background-color: #fff3cd;
            color: #856404;
            border-left: 4px solid #ffc107;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #7f8c8d;
        }

        .empty-state i {
            font-size: 3rem;
            color: #bdc3c7;
            margin-bottom: 1rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .admin-header h1 {
                font-size: 1.8rem;
            }

            .stat-card {
                padding: 1.2rem;
            }

            .stat-card-value {
                font-size: 2rem;
            }

            .table {
                font-size: 0.85rem;
            }

            .table thead th {
                padding: 0.8rem 0.5rem;
            }

            .table tbody td {
                padding: 0.8rem 0.5rem;
            }
        }

        @media (max-width: 576px) {
            .stat-card {
                padding: 1rem;
            }

            .stat-card-value {
                font-size: 1.8rem;
            }

            .table {
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-custom navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}"><i class="fas fa-power-off"></i> GIZMO Store</a>
            <div class="d-flex gap-3 flex-wrap">
                <a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="fas fa-chart-line"></i> Dashboard</a>
                @if(auth()->check() && (method_exists(auth()->user(), 'isMasterAdmin') && auth()->user()->isMasterAdmin() || method_exists(auth()->user(), 'hasAdminScope') && auth()->user()->hasAdminScope('orders')))
                    <a class="nav-link" href="{{ route('admin.orders.index') }}"><i class="fas fa-box"></i> Orders</a>
                @endif
                @php $u = auth()->user(); @endphp
                @if(auth()->check() && (method_exists($u,'isMasterAdmin') && $u->isMasterAdmin() || method_exists($u,'hasAdminScope') && $u->hasAdminScope('products')))
                    <a class="nav-link" href="{{ route('product.index') }}"><i class="fas fa-cubes"></i> Products</a>
                @endif
                @if(auth()->check() && (method_exists($u,'isMasterAdmin') && $u->isMasterAdmin() || method_exists($u,'hasAdminScope') && $u->hasAdminScope('categories')))
                    <a class="nav-link" href="{{ route('category.index') }}"><i class="fas fa-list"></i> Categories</a>
                @endif
                @if(auth()->check() && (method_exists($u,'isMasterAdmin') && $u->isMasterAdmin() || method_exists($u,'hasAdminScope') && $u->hasAdminScope('users')))
                    <a class="nav-link" href="{{ route('users.index') }}"><i class="fas fa-users"></i> Users</a>
                @endif
                <a class="nav-link" href="/"><i class="fas fa-store"></i> View Store</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid admin-container">
        <div class="admin-header">
            <h1><i class="fas fa-chart-line"></i> Dashboard</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="row g-4 mb-5">
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-card-value">{{ $totalOrders }}</div>
                    <div class="stat-card-label"><i class="fas fa-shopping-bag"></i> Total Orders</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-card-value">@currency($totalRevenue)</div>
                    <div class="stat-card-label"><i class="fas fa-dollar-sign"></i> Total Revenue</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-card-value">{{ $productsCount }}</div>
                    <div class="stat-card-label"><i class="fas fa-box"></i> Products</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-card-value">{{ $usersCount }}</div>
                    <div class="stat-card-label"><i class="fas fa-users"></i> Customers</div>
                </div>
            </div>
        </div>

        <!-- Stock Alerts -->
        @if($outOfStockCount > 0 || $lowStockProducts->isNotEmpty())
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Stock Alerts:</strong>
                {{ $outOfStockCount }} product(s) out of stock, {{ $lowStockProducts->count() }} product(s) low on stock (&lt;5)
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Recent Orders Section -->
        <div class="table-card">
            <div class="table-card-header">
                <i class="fas fa-receipt"></i> Recent Orders
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                            <tr>
                                <td><strong>#{{ $order->id }}</strong></td>
                                <td>{{ $order->user->name }}</td>
                                <td><strong>@currency($order->total)</strong></td>
                                <td><span class="badge badge-{{ strtolower($order->status) }}">{{ ucfirst($order->status) }}</span></td>
                                <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-light" title="View Order">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <p>No orders yet</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($totalOrders > 10)
                <div class="p-3 text-center border-top">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">View All Orders</a>
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
