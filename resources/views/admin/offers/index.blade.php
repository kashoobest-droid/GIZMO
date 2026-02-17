<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Manage Offers</title>
    <style>body{background:#f8f9fa}</style>
<head>
<body>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Offers</h3>
        <a href="{{ route('offer.create') }}" class="btn btn-warning">Create Offer</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($offers->contains(fn($o) => !$o->product_id))
        <div class="alert alert-warning mb-3">
            <strong>Not showing on the store?</strong> Offers only appear on the product they're linked to. Edit any offer with "Product: -" below and select a product so it shows on the home page.
        </div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Offer</th>
                    <th>Gift</th>
                    <th>Product</th>
                    <th>Ends At</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($offers as $o)
                    <tr>
                        <td>{{ $o->id }}</td>
                        <td>{{ $o->offer_name ?? '-' }}</td>
                        <td>{{ $o->gift_name }}</td>
                        <td>
                            @if($o->product_id)
                                {{ $o->product->name }}
                            @else
                                <span class="text-danger" title="Select a product in Edit so this offer shows on the store">— None (won't show on store)</span>
                            @endif
                        </td>
                        <td>{{ optional($o->ends_at)->format('Y-m-d H:i') ?? '-' }}</td>
                        <td class="text-end">
                            <a href="{{ route('offer.edit', $o) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form action="{{ route('offer.destroy', $o) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Delete offer?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $offers->links() }}</div>
</div>
</body>
</html>
