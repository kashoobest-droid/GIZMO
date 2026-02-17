<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Create Coupon - KS Tech</title>
</head>
<body class="bg-light">
    <div class="container py-4">
        <h3>Create Coupon</h3>
        @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
        <form action="{{ route('admin.coupons.store') }}" method="POST" class="card p-4" style="max-width: 400px;">
            @csrf
            <div class="mb-3">
                <label class="form-label">Code</label>
                <input type="text" name="code" class="form-control" value="{{ old('code') }}" placeholder="e.g. SAVE10" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Type</label>
                <select name="type" class="form-select" required>
                    <option value="percent" {{ old('type') === 'percent' ? 'selected' : '' }}>Percent off</option>
                    <option value="fixed" {{ old('type') === 'fixed' ? 'selected' : '' }}>Fixed amount off</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Value</label>
                <input type="number" name="value" class="form-control" step="0.01" min="0" value="{{ old('value') }}" required>
                <small class="text-muted">Percent (e.g. 10) or amount (e.g. 5.00)</small>
            </div>
            <div class="mb-3">
                <label class="form-label">Min. purchase (optional)</label>
                <input type="number" name="min_purchase" class="form-control" step="0.01" min="0" value="{{ old('min_purchase') }}" placeholder="0">
            </div>
            <div class="row">
                <div class="col"><label class="form-label">Starts at</label><input type="date" name="starts_at" class="form-control" value="{{ old('starts_at') }}"></div>
                <div class="col"><label class="form-label">Ends at</label><input type="date" name="ends_at" class="form-control" value="{{ old('ends_at') }}"></div>
            </div>
            <div class="mb-3 mt-2">
                <label class="form-label">Use limit (optional)</label>
                <input type="number" name="use_limit" class="form-control" min="1" value="{{ old('use_limit') }}" placeholder="Unlimited">
            </div>
            <button type="submit" class="btn btn-warning">Create</button>
            <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
