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
    <title>Manage Orders - GIZMO Store Admin</title>
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
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            border-bottom: 2px solid #DC143C;
            padding: 1rem 0;
        }

        .navbar-custom .navbar-brand {
            color: #DC143C !important;
            font-weight: 700;
            font-size: 1.4rem;
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

        /* Dark, legible orders table */
        .table {
            margin-bottom: 0;
            font-size: 1.02rem; /* slightly larger for readability */
            color: #e8e8e8;
            border-collapse: separate;
            border-spacing: 0 0.6rem;
        }

        /* make the header row itself dark so it reads as a single bar */
        .table thead { background: transparent; }
        .table thead tr {
            background: linear-gradient(90deg, #0f0f0f 0%, #161616 100%);
            border-bottom: 1px solid rgba(255,255,255,0.04);
            border-radius: 10px 10px 0 0;
            overflow: hidden;
        }

        .table thead th {
            background: transparent;
            color: #f1f1f1;
            font-weight: 700;
            padding: 1rem 1rem;
            text-transform: uppercase;
            font-size: 0.95rem;
            letter-spacing: 0.6px;
        }

        .table tbody tr {
            background: linear-gradient(120deg, #0f0f0f 0%, #161616 100%);
            box-shadow: 0 8px 24px rgba(0,0,0,0.6);
            transition: transform 0.18s ease, box-shadow 0.18s ease;
            border-radius: 10px;
            border: 1px solid rgba(255,255,255,0.03);
        }

        .table tbody tr:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 40px rgba(0,0,0,0.75);
        }

        .table tbody td {
            padding: 1.2rem 1rem;
            vertical-align: middle;
            color: #e8e8e8;
            font-size: 1rem;
        }

        /* Make <strong> inside table cells black for emphasis */
        .table tbody td strong {
            color: #000000 !important;
            font-weight: 700;
        }

        /* Emphasize key cells */
        .order-id { font-weight: 700; color: #DC143C; font-size: 1.05rem; }
        .customer-info { font-weight: 700; color: #e8e8e8; font-size: 1.02rem; }
        .customer-email { color: #bdbdbd; font-size: 0.95rem; }

        /* Highlight Total, Payment, Date cells: bold and black as requested */
        .table tbody td.col-total,
        .table tbody td.col-payment,
        .table tbody td.col-date {
            color: #000000 !important;
            font-weight: 700 !important;
            font-size: 1.02rem;
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
                font-size: 0.95rem;
            }

            .table thead th,
            .table tbody td {
                padding: 0.8rem 0.6rem;
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
                    <div class="stat-card-value">@currency($totalRevenue)</div>
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
                            <th style="width: 6%">Order #</th>
                            <th style="width: 22%">Customer</th>
                            <th style="width: 8%">Items</th>
                            <th style="width: 10%">Total</th>
                            <th style="width: 16%">Payment</th>
                            <th style="width: 10%">Collected</th>
                            <th style="width: 12%">Status</th>
                            <th style="width: 12%">Date</th>
                            <th style="width: 10%; text-align: center;">Actions</th>
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
                                <td class="col-total"><strong>@currency($order->total)</strong></td>
                                <td class="col-payment">
                                    @if($order->payment_method === 'bankak')
                                        Bankak (BOK) - Bank Transfer
                                    @elseif($order->payment_method === 'cod')
                                        Cash on Delivery (COD)
                                    @else
                                        {{ ucfirst($order->payment_method ?? 'N/A') }}
                                    @endif
                                </td>
                                <td>
                                    @if(is_null($order->payment_received_amount))
                                        <span class="text-muted">N/A</span>
                                    @else
                                        <strong>@currency($order->payment_received_amount)</strong>
                                    @endif
                                </td>
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
                                <td class="col-date">{{ $order->created_at->format('M d, Y H:i') }}</td>
                                <td style="text-align: center;">
                                    <a href="{{ route('orders.show', $order) }}" class="btn-view">
                                        <i class="fas fa-eye"></i> View
                                    </a>

                                    @if($order->payment_method === 'bankak')
                                        <div class="mt-2">
                                            <small class="text-muted">Txn: {{ $order->transaction_id ?? '—' }}</small><br>
                                            @if($order->receipt_path)
                                                @php
                                                    $receiptUrl = $order->receipt_path;
                                                    // If receipt_path is not a full URL, assume it's a local storage path
                                                    if (! (str_starts_with($receiptUrl, 'http://') || str_starts_with($receiptUrl, 'https://'))) {
                                                        $receiptUrl = asset('storage/' . ltrim($receiptUrl, '/'));
                                                    }
                                                @endphp
                                                <a href="{{ $receiptUrl }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary mt-1">View Receipt</a>
                                            @endif
                                        </div>

                                        @if($order->payment_status === 'awaiting_admin_approval')
                                            <div class="d-flex gap-2 justify-content-center mt-2">
                                                <button type="button" class="btn btn-sm btn-success js-admin-action-btn" data-action="{{ route('admin.orders.approvePayment', $order) }}" data-order-id="{{ $order->id }}" data-action-label="APPROVE">Approve</button>
                                                <button type="button" class="btn btn-sm btn-danger js-admin-action-btn" data-action="{{ route('admin.orders.rejectPayment', $order) }}" data-order-id="{{ $order->id }}" data-action-label="REJECT">Reject</button>
                                            </div>
                                        @endif
                                    @endif
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
        <!-- Admin action confirmation modal (Approve) -->
        <div class="modal fade" id="confirmActionModal" tabindex="-1" aria-labelledby="confirmActionModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header" style="background: linear-gradient(135deg, #DC143C 0%, #8B0000 100%); color: #fff; border-bottom: 4px solid #DC143C;">
                        <h5 class="modal-title" id="confirmActionModalLabel">Confirm Action</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p id="confirmActionMessage" class="mb-0"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="confirmActionBtn" class="btn btn-danger">Confirm</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reject payment modal with reason dropdown -->
        <div class="modal fade" id="rejectPaymentModal" tabindex="-1" aria-labelledby="rejectPaymentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form id="rejectPaymentForm" method="POST" action="">
                        <div class="modal-header" style="background: linear-gradient(135deg, #DC143C 0%, #8B0000 100%); color: #fff; border-bottom: 4px solid #DC143C;">
                            <h5 class="modal-title" id="rejectPaymentModalLabel">Reject Payment</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @csrf
                            <p>Select the reason for rejecting this payment. This reason will be included in the rejection email sent to the customer.</p>
                            <div class="mb-3">
                                <label for="rejectReason" class="form-label">Rejection Reason</label>
                                <select id="rejectReason" name="reason" class="form-select" required>
                                    <option value="">-- Choose a reason --</option>
                                    <option>Transaction ID not found in bank records.</option>
                                    <option>Screenshot is blurry or unreadable.</option>
                                    <option>Amount transferred does not match order total.</option>
                                    <option>Duplicate Transaction ID (already used for another order).</option>
                                    <option>Other - see details below</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="rejectReasonDetails" class="form-label">Additional details (optional)</label>
                                <textarea id="rejectReasonDetails" name="reason_details" class="form-control" rows="3" placeholder="Optional details to include with the reason..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Reject Payment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <form id="adminActionForm" method="POST" style="display:none;">
            @csrf
        </form>

        <script>
            (function(){
                const confirmEl = document.getElementById('confirmActionModal');
                const bsConfirm = new bootstrap.Modal(confirmEl, { backdrop: 'static' });

                const rejectEl = document.getElementById('rejectPaymentModal');
                const bsReject = new bootstrap.Modal(rejectEl, { backdrop: 'static' });

                let pendingActionUrl = null;
                let pendingOrderId = null;

                document.querySelectorAll('.js-admin-action-btn').forEach(btn => {
                    btn.addEventListener('click', function(){
                        pendingActionUrl = this.getAttribute('data-action');
                        pendingOrderId = this.getAttribute('data-order-id');
                        const label = this.getAttribute('data-action-label') || 'CONFIRM';

                        if (label === 'REJECT') {
                            // Open reject modal and set form action
                            const form = document.getElementById('rejectPaymentForm');
                            form.action = pendingActionUrl;
                            // clear previous selections
                            document.getElementById('rejectReason').value = '';
                            document.getElementById('rejectReasonDetails').value = '';
                            bsReject.show();
                            return;
                        }

                        const message = `Are you sure you want to <strong>${label}</strong> order #${pendingOrderId}?`;
                        document.getElementById('confirmActionMessage').innerHTML = message;
                        const confirmBtn = document.getElementById('confirmActionBtn');
                        confirmBtn.className = label === 'APPROVE' ? 'btn btn-success' : 'btn btn-danger';
                        confirmBtn.innerText = label;
                        bsConfirm.show();
                    });
                });

                document.getElementById('confirmActionBtn').addEventListener('click', function(){
                    if (!pendingActionUrl) return;
                    const form = document.getElementById('adminActionForm');
                    form.action = pendingActionUrl;
                    form.submit();
                });

                // When reject form is submitted, combine select + details into a single reason string
                document.getElementById('rejectPaymentForm').addEventListener('submit', function(e){
                    const select = document.getElementById('rejectReason');
                    const details = document.getElementById('rejectReasonDetails').value.trim();
                    if (!select.value) {
                        e.preventDefault();
                        alert('Please select a rejection reason.');
                        return;
                    }
                    // create a hidden input carrying the merged reason
                    let reasonText = select.value;
                    if (details) reasonText = reasonText + ' — ' + details;
                    const hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.name = 'reason';
                    hidden.value = reasonText;
                    this.appendChild(hidden);
                });
            })();
        </script>
