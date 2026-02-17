<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <title>Manage Orders - KS Tech Admin</title>
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
            background: radial-gradient(circle, rgba(220, 20, 60, 0.1) 0%, transparent 70%);
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
            opacity: 0.7;
        }

        /* Chart Container */
        .chart-card {
            background: linear-gradient(135deg, #1e1e1e 0%, #2a2a2a 100%);
            border-radius: 12px;
            padding: 1.8rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
            margin-bottom: 2rem;
            border: 1px solid rgba(255, 153, 0, 0.2);
        }

        .chart-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #e0e0e0;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .chart-title i {
            color: #DC143C;
            font-size: 1.4rem;
        }

        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
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
            color: #000000;
        }

        .table thead {
            background: rgba(30, 30, 30, 0.8);
            border-bottom: 2px solid rgba(220, 20, 60, 0.2);
        }

        .table thead th {
            color: #000000;
            font-weight: 600;
            padding: 1.2rem 1rem;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .table tbody tr {
            border-bottom: 1px solid rgba(255, 153, 0, 0.1);
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: rgba(255, 153, 0, 0.05);
        }

        .table tbody td {
            padding: 1.2rem 1rem;
            vertical-align: middle;
            color: #000000;
        }

        .order-id {
            font-weight: 600;
            color: #DC143C;
        }

        .customer-info {
            font-weight: 600;
            color: #000000;
        }

        .customer-email {
            color: #555555;
            font-size: 0.9rem;
        }

        /* Status Badges */
        .badge-pending {
            background: #DC143C;
            color: #ffffff;
            padding: 0.5rem 0.8rem;
            border-radius: 6px;
            font-weight: 500;
        }

        .badge-processing {
            background: #0066cc;
            color: #ffffff;
            padding: 0.5rem 0.8rem;
            border-radius: 6px;
            font-weight: 500;
        }

        .badge-shipped {
            background: #00ccff;
            color: #ffffff;
            padding: 0.5rem 0.8rem;
            border-radius: 6px;
            font-weight: 500;
        }

        .badge-delivered {
            background: #00a86b;
            color: #ffffff;
            padding: 0.5rem 0.8rem;
            border-radius: 6px;
            font-weight: 500;
        }

        .badge-cancelled {
            background: #ff4444;
            color: #ffffff;
            padding: 0.5rem 0.8rem;
            border-radius: 6px;
            font-weight: 500;
        }

        /* Status Select */
        .status-select {
            padding: 0.6rem 0.8rem;
            border: 2px solid #DC143C;
            border-radius: 6px;
            font-weight: 600;
            background: #1a1a1a;
            color: #ffffff;
            cursor: pointer;
            transition: none;
        }

        .status-select:focus {
            outline: none;
            border-color: #FF6B6B;
            box-shadow: 0 0 0 3px rgba(220, 20, 60, 0.2);
            background: #1a1a1a;
        }

        .status-select:hover {
            background: #1a1a1a;
            border-color: #DC143C;
        }

        /* Action Button */
        .btn-view {
            background: linear-gradient(135deg, #DC143C 0%, #8B0000 100%);
            color: #000000;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .btn-view:hover {
            background: linear-gradient(135deg, #8B0000 0%, #6B0000 100%);
            color: #000000;
            transform: translateY(-2px);
        }

        /* Alert */
        .alert {
            border-radius: 10px;
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        /* Pagination */
        .pagination {
            gap: 0.5rem;
        }

        .pagination .page-link {
            border: 1px solid #555555;
            border-radius: 6px;
            color: #DC143C;
            background: #1a1a1a;
        }

        .pagination .page-link:hover {
            background-color: #DC143C;
            border-color: #DC143C;
            color: #000000;
        }

        .pagination .page-item.active .page-link {
            background-color: #DC143C;
            border-color: #DC143C;
            color: #000000;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #b0b0b0;
        }

        .empty-state i {
            font-size: 3rem;
            color: #666666;
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

            .charts-grid {
                grid-template-columns: 1fr;
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

            .admin-header h1 {
                font-size: 1.5rem;
            }

            .stat-card {
                padding: 1.2rem;
            }

            .stat-card-value {
                font-size: 2rem;
            }

            .table {
                font-size: 0.75rem;
            }

            .table thead th,
            .table tbody td {
                padding: 0.5rem;
            }

            .status-select {
                padding: 0.3rem 0.5rem;
                font-size: 0.8rem;
            }

            .btn-view {
                padding: 0.4rem 0.8rem;
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-custom navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}"><i class="fas fa-power-off"></i> KS TECH Admin</a>
            <div class="d-flex gap-3 flex-wrap">
                <a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="fas fa-chart-line"></i> Dashboard</a>
                <a class="nav-link" href="{{ route('admin.orders.index') }}"><i class="fas fa-box"></i> Orders</a>
                <a class="nav-link" href="{{ route('product.index') }}"><i class="fas fa-cubes"></i> Products</a>
                <a class="nav-link" href="{{ route('category.index') }}"><i class="fas fa-list"></i> Categories</a>
                <a class="nav-link" href="{{ route('users.index') }}"><i class="fas fa-users"></i> Users</a>
                <a class="nav-link" href="/"><i class="fas fa-store"></i> View Store</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid admin-container">
        <!-- Header -->
        <div class="admin-header">
            <h1><i class="fas fa-shopping-bag"></i> Order Management</h1>
        </div>

        <!-- Success Message -->
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
                    <div class="stat-card-label"><i class="fas fa-boxes"></i> Total Orders</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-card-value">${{ number_format($totalRevenue, 0) }}</div>
                    <div class="stat-card-label"><i class="fas fa-dollar-sign"></i> Total Revenue</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-card-value">{{ $pendingOrders }}</div>
                    <div class="stat-card-label"><i class="fas fa-clock"></i> Pending Orders</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-card-value">{{ $completedOrders }}</div>
                    <div class="stat-card-label"><i class="fas fa-check-circle"></i> Completed Orders</div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="charts-grid">
            <!-- Orders by Status Chart -->
            <div class="chart-card">
                <div class="chart-title">
                    <i class="fas fa-pie-chart"></i> Orders by Status
                </div>
                <canvas id="statusChart" style="max-height: 250px;"></canvas>
            </div>

            <!-- Revenue Trend Chart -->
            <div class="chart-card">
                <div class="chart-title">
                    <i class="fas fa-chart-line"></i> Revenue Trend (Last 7 Days)
                </div>
                <canvas id="revenueChart" style="max-height: 250px;"></canvas>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="table-card">
            <div class="table-card-header">
                <i class="fas fa-list-ul"></i> All Orders
                <span class="badge bg-white text-dark ms-auto">{{ $orders->total() }} Orders</span>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 8%">Order #</th>
                            <th style="width: 25%">Customer</th>
                            <th style="width: 10%">Items</th>
                            <th style="width: 12%">Total</th>
                            <th style="width: 15%">Status</th>
                            <th style="width: 15%">Date</th>
                            <th style="width: 15%; text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td><span class="order-id">#{{ $order->id }}</span></td>
                                <td>
                                    <div class="customer-info">{{ $order->user->name }}</div>
                                    <div class="customer-email">{{ $order->user->email }}</div>
                                </td>
                                <td>
                                    <span style="background: #333333; padding: 0.4rem 0.8rem; border-radius: 6px; font-weight: 500; color: #ffffff;">
                                        {{ $order->items->count() }} item(s)
                                    </span>
                                </td>
                                <td><strong>${{ number_format($order->total, 2) }}</strong></td>
                                <td>
                                    <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="status-select" onchange="this.form.submit()">
                                            @foreach(['pending','processing','shipped','delivered','cancelled'] as $s)
                                                <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>
                                                    @if($s === 'pending')
                                                        <span class="badge-pending">{{ ucfirst($s) }}</span>
                                                    @elseif($s === 'processing')
                                                        <span class="badge-processing">{{ ucfirst($s) }}</span>
                                                    @elseif($s === 'shipped')
                                                        <span class="badge-shipped">{{ ucfirst($s) }}</span>
                                                    @elseif($s === 'delivered')
                                                        <span class="badge-delivered">{{ ucfirst($s) }}</span>
                                                    @else
                                                        <span class="badge-cancelled">{{ ucfirst($s) }}</span>
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>
                                <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                                <td style="text-align: center;">
                                    <a href="{{ route('orders.show', $order) }}" class="btn-view">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <p>No orders yet</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3 d-flex justify-content-center">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Status Chart Data
        const statusData = {!! json_encode($ordersByStatus) !!};
        const statusLabels = Object.keys(statusData).map(s => s.charAt(0).toUpperCase() + s.slice(1));
        const statusValues = Object.values(statusData);

        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusValues,
                    backgroundColor: [
                        'rgba(255, 153, 0, 0.8)',
                        'rgba(52, 152, 219, 0.8)',
                        'rgba(52, 211, 153, 0.8)',
                        'rgba(34, 197, 94, 0.8)',
                        'rgba(239, 68, 68, 0.8)'
                    ],
                    borderColor: [
                        'rgba(255, 153, 0, 1)',
                        'rgba(52, 152, 219, 1)',
                        'rgba(52, 211, 153, 1)',
                        'rgba(34, 197, 94, 1)',
                        'rgba(239, 68, 68, 1)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: { size: 12, weight: '600' },
                            padding: 15,
                            color: '#e0e0e0'
                        }
                    }
                }
            }
        });

        // Revenue Chart Data
        const revenueData = {!! json_encode($last7Days) !!};
        const revenueDates = revenueData.map(item => new Date(item.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
        const revenueValues = revenueData.map(item => parseFloat(item.revenue));

        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: revenueDates,
                datasets: [{
                    label: 'Daily Revenue',
                    data: revenueValues,
                    borderColor: '#DC143C',
                    backgroundColor: 'rgba(255, 153, 0, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 6,
                    pointBackgroundColor: '#DC143C',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        labels: {
                            font: { size: 12, weight: '600' },
                            color: '#e0e0e0'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toFixed(0);
                            },
                            color: '#b0b0b0',
                            font: { size: 11 }
                        },
                        grid: {
                            color: 'rgba(255, 153, 0, 0.1)'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#b0b0b0',
                            font: { size: 11 }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>
