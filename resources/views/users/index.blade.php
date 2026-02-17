<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Manage Users - Gizmo SD</title>
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
            background: linear-gradient(135deg, #ffffff 0%, #ffffff 100%);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
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
            background: rgba(255, 153, 0, 0.15);
        }

        /* Main Container */
        .admin-container {
            padding: 2rem 1rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Header */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
            background: linear-gradient(135deg, #1e1e1e 0%, #2a2a2a 100%);
            padding: 2rem;
            border-radius: 12px;
            border-left: 4px solid #DC143C;
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
        .btn-back {
            background: linear-gradient(135deg, #DC143C 0%, #8B0000 100%);
            color: white;
            border: none;
            padding: 0.7rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-back:hover {
            color: white;
            background: linear-gradient(135deg, #8B0000 0%, #6B0000 100%);
            transform: translateY(-2px);
        }

        /* Table Card */
        .table-card {
            background: linear-gradient(135deg, #1e1e1e 0%, #2a2a2a 100%);
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
            overflow: hidden;
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

        .table {
            margin-bottom: 0;
            font-size: 0.95rem;
            color: #e0e0e0;
        }

        .table thead {
            background: rgba(30, 30, 30, 0.8);
            border-bottom: 2px solid rgba(255, 153, 0, 0.2);
        }

        .table thead th {
            color: #000000;
            font-weight: 600;
            padding: 1.2rem 1rem;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            vertical-align: middle;
        }

        .table tbody tr {
            border-bottom: 1px solid rgba(255, 153, 0, 0.1);
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: rgba(255, 153, 0, 0.05);
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            color: #000000;
        }

        /* User Info */
        .user-name {
            font-weight: 600;
            color: #000000;
        }

        .user-email {
            color: #b0b0b0;
            font-size: 0.9rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #DC143C 0%, #8B0000 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            margin-right: 0.8rem;
        }

        .badge-admin {
            background: #000000;
            color: #ff0000;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.85rem;
        }

        .badge-customer {
            background: rgba(149, 165, 166, 0.2);
            color: #b0b0b0;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.85rem;
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
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
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
            color: #00ff88;
        }

        .alert-success {
            background: linear-gradient(135deg, #2a2a2a 0%, #323232 100%);
            color: #00ff88;
        }

        /* Stats Section */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-item {
            background: linear-gradient(135deg, #1e1e1e 0%, #2a2a2a 100%);
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
            text-align: center;
            border: 1px solid rgba(220, 20, 60, 0.2);
            border-left: 4px solid #DC143C;
        }

        .stat-item-number {
            font-size: 2rem;
            font-weight: 700;
            color: #DC143C;
            margin-bottom: 0.5rem;
        }

        .stat-item-label {
            color: #b0b0b0;
            font-weight: 500;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .stat-item-label i {
            color: #DC143C;
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

            .btn-back {
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

            .user-avatar {
                width: 35px;
                height: 35px;
                font-size: 0.8rem;
                margin-right: 0.5rem;
            }

            .btn-action {
                padding: 0.3rem 0.6rem;
                font-size: 0.7rem;
                margin-right: 0.2rem;
            }

            .table tbody td:nth-child(n+5) {
                display: none;
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
            <h1><i class="fas fa-users"></i> User Management</h1>
            <a href="/" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to Store
            </a>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Users Statistics -->
        <div class="stats-row">
            <div class="stat-item">
                <div class="stat-item-number">{{ count($users) }}</div>
                <div class="stat-item-label"><i class="fas fa-users"></i> Total Users</div>
            </div>
            <div class="stat-item">
                <div class="stat-item-number">{{ count($users->where('is_admin', true)) }}</div>
                <div class="stat-item-label"><i class="fas fa-crown"></i> Administrators</div>
            </div>
            <div class="stat-item">
                <div class="stat-item-number">{{ count($users->where('is_admin', false)) }}</div>
                <div class="stat-item-label"><i class="fas fa-user-check"></i> Customers</div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="table-card">
            <div class="table-card-header">
                <i class="fas fa-list-ul"></i> Users List
                <span class="badge bg-white text-dark ms-auto">{{ count($users) }} Users</span>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 30%">User</th>
                            <th style="width: 25%">Email</th>
                            <th style="width: 15%">Contact</th>
                            <th style="width: 15%">Location</th>
                            <th style="width: 10%; text-align: center;">Role</th>
                            <th style="width: 10%; text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <div class="user-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                        <div>
                                            <div class="user-name">{{ $user->name }}</div>
                                            <div class="user-email">ID: #{{ $user->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone ?? '-' }}</td>
                                <td>
                                    @if($user->country)
                                        <span style="font-size: 0.9rem; color: #7f8c8d;">
                                            <i class="fas fa-map-marker-alt"></i> {{ $user->country }}
                                            @if($user->city_area), {{ $user->city_area }}@endif
                                        </span>
                                    @else
                                        <span style="color: #95a5a6;">-</span>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    @if($user->is_admin)
                                        <span class="badge-admin"><i class="fas fa-crown"></i> Admin</span>
                                    @else
                                        <span class="badge-customer"><i class="fas fa-user"></i> Customer</span>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-action btn-edit" title="Edit User">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-action btn-delete" title="Delete User">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="empty-state">
                                    <i class="fas fa-users"></i>
                                    <p>No users found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
