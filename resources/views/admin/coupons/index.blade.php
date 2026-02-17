<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Coupons - KS Tech</title>
</head>
<body class="bg-light">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Coupons</h3>
            <a href="{{ route('admin.coupons.create') }}" class="btn btn-warning">Create Coupon</a>
        </div>
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        <div class="card">
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead><tr><th>Code</th><th>Type</th><th>Value</th><th>Min purchase</th><th>Used</th><th>Valid</th><th></th></tr></thead>
                    <tbody>
                        @forelse($coupons as $c)
                            <tr>
                                <td><code>{{ $c->code }}</code></td>
                                <td>{{ $c->type === 'percent' ? 'Percent' : 'Fixed' }}</td>
                                <td>{{ $c->type === 'percent' ? $c->value . '%' : '$' . number_format($c->value, 2) }}</td>
                                <td>{{ $c->min_purchase ? '$' . number_format($c->min_purchase, 2) : '-' }}</td>
                                <td>{{ $c->used_count }}{{ $c->use_limit ? ' / ' . $c->use_limit : '' }}</td>
                                <td>
                                    @if($c->starts_at && now()->lessThan($c->starts_at))<span class="text-muted">Starts {{ $c->starts_at->format('M j') }}</span>
                                    @elseif($c->ends_at && now()->greaterThan($c->ends_at))<span class="text-danger">Expired</span>
                                    @else<span class="text-success">Active</span>@endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.coupons.destroy', $c) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this coupon?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted">No coupons yet. <a href="{{ route('admin.coupons.create') }}">Create one</a>.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-2">{{ $coupons->links() }}</div>
    </div>
</body>
</html>
