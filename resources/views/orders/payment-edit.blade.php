<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Update Payment for Order #{{ $order->id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body{background:#f7f7f8;color:#111;font-family:Inter,Arial,Helvetica,sans-serif;padding:2rem}</style>
</head>
<body>
<div class="container" style="max-width:720px">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Update Payment for Order #{{ $order->id }}</h4>
            <p class="text-muted">Please provide the correct transaction ID and upload a clear screenshot/receipt. This link is temporary and secure.</p>

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ $postUrl }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Transaction ID</label>
                    <input type="text" name="transaction_id" class="form-control" value="{{ old('transaction_id', $order->transaction_id) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Receipt / Screenshot (image)</label>
                    <input type="file" name="receipt" accept="image/*" class="form-control">
                    @if(!empty($order->receipt_url))
                        <small class="text-muted">Current receipt: <a href="{{ $order->receipt_url }}" target="_blank" rel="noopener">View</a></small>
                    @elseif(!empty($order->receipt_path))
                        <small class="text-muted">Current receipt: <a href="{{ (str_starts_with($order->receipt_path, 'http') ? $order->receipt_path : asset('storage/' . ltrim($order->receipt_path, '/'))) }}" target="_blank" rel="noopener">View</a></small>
                    @endif
                </div>

                <div class="mb-3">
                    <label class="form-label">Optional note</label>
                    <textarea name="note" class="form-control" rows="3">{{ old('note') }}</textarea>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-primary" type="submit">Submit Update</button>
                    <a href="{{ url('/') }}" class="btn btn-secondary">Return to Store</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
