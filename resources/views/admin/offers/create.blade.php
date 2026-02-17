<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Create Offer</title>
</head>
<body>
<div class="container py-4">
    <h3>Create Offer</h3>
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('offer.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">Product <span class="text-danger">*</span></label>
            <select name="product_id" class="form-select" required>
                <option value="">-- Select a product (offer will show on this product's card) --</option>
                @foreach($products as $p)
                    <option value="{{ $p->id }}" {{ old('product_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                @endforeach
            </select>
            <div class="form-text">The offer will appear on the home page and product page only for this product.</div>
        </div>
        <div class="mb-3">
            <label class="form-label">Offer Name</label>
            <input name="offer_name" class="form-control" value="{{ old('offer_name') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Gift Name</label>
            <input name="gift_name" class="form-control" value="{{ old('gift_name') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control">{{ old('description') }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Image</label>
            <input type="file" name="image" class="form-control">
        </div>
        <div class="row">
            <div class="col">
                <label class="form-label">Starts At</label>
                <input type="datetime-local" name="starts_at" value="{{ old('starts_at') }}" class="form-control">
            </div>
            <div class="col">
                <label class="form-label">Ends At</label>
                <input type="datetime-local" name="ends_at" value="{{ old('ends_at') }}" class="form-control">
            </div>
        </div>
        <div class="mt-3">
            <button class="btn btn-warning">Create</button>
            <a href="{{ route('offer.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>
