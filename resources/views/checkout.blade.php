<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="/css/gizmo_sudan.css">
    <title>Checkout - Gizmo Store</title>
<style>
    :root {
        --bg-primary: #ffffff;
        --bg-secondary: #f8f9fa;
        --card-bg: #ffffff;
        --text-primary: #1a1a1a;
        --text-secondary: #666666;
        --border-color: #dee2e6;
        --accent-color: #DC143C;
    }

    html.dark-mode {
        --bg-primary: #121212;
        --bg-secondary: #1d1d1d;
        --card-bg: #242424;
        --text-primary: #e8e8e8;
        --text-secondary: #a0a0a0;
        --border-color: #3a3a3a;
    }

    body {
        background-color: var(--bg-secondary);
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        color: var(--text-primary);
        transition: background-color 0.3s, color 0.3s;
    }

    /* Navbar fixes */
    .navbar-custom {
        background-color: var(--bg-primary);
        border-bottom: 3px solid var(--accent-color);
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        padding: 0.75rem 0;
    }

    .nav-link {
        color: var(--text-primary) !important;
        font-weight: 500;
    }

    /* Missing Component Styles */
    .checkout-card {
        background-color: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
    }

    .cart-item-img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid var(--border-color);
    }

    .btn-place-order {
        background-color: var(--accent-color);
        color: white;
        border: none;
        padding: 12px;
        font-weight: 700;
        border-radius: 8px;
        transition: transform 0.2s, background-color 0.2s;
    }

    .btn-place-order:hover {
        background-color: #b01030;
        color: white;
        transform: translateY(-1px);
    }

    /* Dark Mode Toggle Button */
    .mode-toggle {
        background: var(--bg-secondary);
        border: 1px solid var(--border-color);
        color: var(--text-primary);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    /* Form Overrides for Dark Mode */
    .form-control, .form-select, .input-group-text {
        background-color: var(--bg-primary);
        color: var(--text-primary);
        border-color: var(--border-color);
    }

    .form-control:focus {
        background-color: var(--bg-primary);
        color: var(--text-primary);
    }

    .form-check-input:checked {
        background-color: var(--accent-color);
        border-color: var(--accent-color);
    }

    .text-muted { color: var(--text-secondary) !important; }
    hr { border-top-color: var(--border-color); opacity: 1; }
</style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-custom navbar-expand-lg">
        <div class="container d-flex align-items-center justify-content-between">
            <a class="navbar-brand" href="/"><i class="fas fa-power-off"></i> Gizmo Store</a>
            <button class="mode-toggle" id="modeToggle" title="Toggle Dark/Light Mode">
                <i class="fas fa-moon"></i>
            </button>
            <div class="d-flex gap-3 align-items-center">
                <a class="nav-link" href="/">
                    <i class="fas fa-home"></i> Home
                </a>
                <a class="nav-link" href="{{ route('cart.index') }}">
                    <i class="fas fa-shopping-cart"></i> Cart
                </a>
                <a class="nav-link" href="{{ route('profile.edit') }}">
                    <i class="fas fa-user"></i> Profile
                </a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <h2 class="mb-4"><i class="fas fa-check-circle" style="color: #DC143C;"></i> Checkout</h2>

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('orders.store') }}" method="POST" id="checkoutForm" enctype="multipart/form-data">
            @csrf
            <div class="row">
            <div class="col-lg-8">
                <div class="checkout-card p-4 mb-4">
                    <h5 class="mb-3"><i class="fas fa-map-marker-alt" style="color: #DC143C;"></i> Shipping Address</h5>
                    @if(!empty($needsAddress) && $needsAddress)
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i> Shipping Address is required. Please provide your address below to continue.
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Country</label>
                            <input type="text" name="country" class="form-control" value="{{ old('country', auth()->user()->country) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Street</label>
                            <input type="text" name="street_name" class="form-control" value="{{ old('street_name', auth()->user()->street_name) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Building / Villa</label>
                            <input type="text" name="building_name" class="form-control" value="{{ old('building_name', auth()->user()->building_name) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Floor / Apartment <small class="text-muted">(optional)</small></label>
                            <input type="text" name="floor_apartment" class="form-control" value="{{ old('floor_apartment', auth()->user()->floor_apartment) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Landmark <small class="text-muted">(optional)</small></label>
                            <input type="text" name="landmark" class="form-control" value="{{ old('landmark', auth()->user()->landmark) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">City / Area</label>
                            <input type="text" name="city_area" class="form-control" value="{{ old('city_area', auth()->user()->city_area) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" id="address_phone" name="phone" class="form-control" value="{{ old('phone', auth()->user()->phone) }}">
                        </div>
                        <div class="mb-3">
                            <button type="button" id="saveAddressBtn" class="btn btn-primary">Save Address</button>
                        </div>
                    @else
                        <p class="text-muted mb-0">{{ auth()->user()->formatShippingAddress() }}</p>
                        @if(auth()->user()->phone)
                            <p class="mb-0 mt-2"><i class="fas fa-phone" style="color: #DC143C;"></i> {{ auth()->user()->phone }}</p>
                        @endif
                        <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-secondary mt-3">
                            <i class="fas fa-edit me-1"></i> Update Address
                        </a>
                    @endif
                </div>

                @if(! auth()->user()->phone)
                    <div class="alert alert-warning mb-3">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Please make sure your phone number is available in your profile before placing an order.
                        <a href="{{ route('profile.edit') }}" class="ms-2">Update profile</a>
                    </div>
                @endif

                <div class="checkout-card p-4">
                    <h5 class="mb-3"><i class="fas fa-box" style="color: #DC143C;"></i> Order Items</h5>
                    @foreach($cartItems as $item)
                        <div class="d-flex align-items-center gap-3 py-2 border-bottom">
                            @if($item->product->images->first())
                                <img src="{{ filter_var($item->product->images->first()->image_path, FILTER_VALIDATE_URL) ? $item->product->images->first()->image_path : asset($item->product->images->first()->image_path) }}" alt="" class="cart-item-img">
                            @else
                                <img src="https://via.placeholder.com/60?text=No+Image" alt="" class="cart-item-img">
                            @endif
                            <div class="flex-grow-1">
                                <strong>{{ $item->product->name }}</strong><br>
                                <small class="text-muted">Qty: {{ $item->quantity }} × @currency($item->product->price)</small>
                            </div>
                            <strong>@currency($item->product->price * $item->quantity)</strong>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="col-lg-4">
                <div class="checkout-card p-4">
                    <h5 class="mb-3"><i class="fas fa-receipt" style="color: #DC143C;"></i> Order Summary</h5>
                    <p class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span class="fw-bold" style="color: #DC143C;">@currency($subtotal)</span>
                    </p>
                    @if(isset($coupon) && $coupon && $discount > 0)
                        <p class="d-flex justify-content-between mb-2 text-success">
                            <span>Discount ({{ $coupon->code }})</span>
                            <span>-@currency($discount)</span>
                        </p>
                    @endif
                    <p class="d-flex justify-content-between mb-2">
                        <span>Total</span>
                        <span class="fw-bold fs-5" style="color: #DC143C;">@currency($total ?? $subtotal)</span>
                    </p>
                    <hr>
                    
                        <div class="mb-3">
                            <label class="form-label small"><i class="fas fa-tag me-1"></i>Coupon code (optional)</label>
                            <div class="input-group input-group-sm">
                                <input type="text" name="coupon_code" class="form-control" placeholder="Enter code" value="{{ optional($coupon)->code ?? old('coupon_code') }}">
                            </div>
                            <p class="small text-muted mb-0 mt-1">Enter your code and place order to apply.</p>
                        </div>
                            <hr>
                            <div class="mb-3">
                                <label class="form-label small"><i class="fas fa-credit-card me-1"></i> Payment Method</label>
                                @php
                                    $city = strtolower(trim(auth()->user()->city_area ?? ''));
                                    $codAvailable = (strpos($city, 'port') !== false) || (strpos($city, 'port sudan') !== false) || (strpos($city, 'portsudan') !== false);
                                @endphp
                                <div class="d-flex flex-column">
                                    <!-- Card (online) temporarily disabled -->
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="radio" name="payment_method" id="pm_bankak" value="bankak" {{ old('payment_method', 'bankak') === 'bankak' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="pm_bankak"><img src="https://res.cloudinary.com/dgrnbtgts/image/upload/v1771513567/bok_zmc15z.png" alt="BOK" style="height:20px; margin-right:8px; vertical-align:middle;">Bankak (BOK) - Bank Transfer</label>
                                    </div>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="radio" name="payment_method" id="pm_cod" value="cod" {{ old('payment_method') === 'cod' ? 'checked' : '' }} {{ $codAvailable ? '' : 'disabled' }}>
                                        <label class="form-check-label" for="pm_cod">Cash on Delivery (COD) @if(! $codAvailable) <small class="text-muted">(Only Port Sudan)</small> @endif</label>
                                    </div>
                                    @error('payment_method')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div id="bankakFields" style="display:none;">
                                <div class="alert alert-light">Please transfer to: <strong>{{ env('BOK_ACCOUNT_NUMBER', 'BOK-ACCOUNT-XXXXXXXX') }}</strong> ({{ env('BOK_ACCOUNT_NAME', 'GIZMO Store') }}). In the transfer note include your Order reference.</div>

                                <div class="mb-3">
                                    <label class="form-label">Bankak Transaction ID</label>
                                    <input type="text" name="transaction_id" class="form-control @error('transaction_id') is-invalid @enderror" value="{{ old('transaction_id') }}">
                                    @error('transaction_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Upload Transfer Screenshot</label>
                                    <input type="file" name="receipt" accept="image/*" class="form-control @error('receipt') is-invalid @enderror">
                                    @error('receipt')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        <div class="mb-3">
                            <label class="form-label small"><i class="fas fa-pen me-1"></i>Order notes (optional)</label>
                            <textarea name="notes" class="form-control form-control-sm" rows="2" placeholder="Special instructions..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-place-order w-100">
                            <i class="fas fa-lock me-2"></i> Place Order
                        </button>
                    
                    <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                        <i class="fas fa-arrow-left me-2"></i> Back to Cart
                    </a>
                </div>
            </div>
            </div>
        </form>
    </div>

    <!-- Dark Mode Script -->
    <script>
        const STORAGE_KEY = 'gizmo-store-dark-mode';
        const html = document.documentElement;
        const modeToggle = document.getElementById('modeToggle');

        // Initialize dark mode
        function initializeDarkMode() {
            const isDarkMode = localStorage.getItem(STORAGE_KEY) === 'true' || 
                             (!localStorage.getItem(STORAGE_KEY) && window.matchMedia('(prefers-color-scheme: dark)').matches);
            
            if (isDarkMode) {
                html.classList.add('dark-mode');
                updateModeToggle(true);
            } else {
                html.classList.remove('dark-mode');
                updateModeToggle(false);
            }
        }

        // Update toggle button
        function updateModeToggle(isDark) {
            modeToggle.innerHTML = isDark ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
            modeToggle.title = isDark ? 'Switch to Light Mode' : 'Switch to Dark Mode';
        }

        // Toggle dark mode
        modeToggle.addEventListener('click', () => {
            const isDarkMode = html.classList.toggle('dark-mode');
            localStorage.setItem(STORAGE_KEY, isDarkMode);
            updateModeToggle(isDarkMode);
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', initializeDarkMode);
    </script>
    <script>
        // Payment method toggle logic
        function updatePaymentFields() {
            const bankakFields = document.getElementById('bankakFields');
            const pmBankak = document.getElementById('pm_bankak');
            if (!bankakFields || !pmBankak) return;
            bankakFields.style.display = pmBankak.checked ? 'block' : 'none';
        }

        document.addEventListener('DOMContentLoaded', function() {
            const radios = document.querySelectorAll('input[name="payment_method"]');
            radios.forEach(r => r.addEventListener('change', updatePaymentFields));
            updatePaymentFields();
        });
    </script>
    
</body>
</html>
