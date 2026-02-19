<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmed;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderShipped;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Verification;

/**
 * OrderController - Handles all order-related operations
 * 
 * Responsibilities:
 * - Display checkout page
 * - Create new orders from cart
 * - List user's orders
 * - Show order details
 * - Admin order management and status updates
 * 
 * Security:
 * - Users can only view/access their own orders
 * - Admins can manage all orders
 * - All monetary calculations validated
 * - Stock verified before order creation
 */
class OrderController extends Controller
{
    /**
     * Display checkout page with cart items and totals
     * 
     * @param Request $request
     * @return \Illuminate\View\View|Redirect
     * 
     * Validation:
     * - User must be authenticated (via middleware)
     * - Cart must not be empty
     * - All items must have available stock
     * - Coupon (if provided) must be valid and applicable
     */
    public function checkout(Request $request)
    {
        $user = Auth::user();
        $cartItems = $user->cartItems()->with('product.images', 'product.category')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        

        // Determine whether the user needs to provide a shipping address
        $needsAddress = method_exists($user, 'hasShippingAddress') && ! $user->hasShippingAddress();

        // Validate stock
        foreach ($cartItems as $item) {
            if ($item->product->quantity < $item->quantity) {
                return redirect()->route('cart.index')->with('error', "Not enough stock for: {$item->product->name}. Available: {$item->product->quantity}");
            }
        }

        $subtotal = $cartItems->sum(fn ($i) => $i->product->price * $i->quantity);
        $coupon = null;
        $discount = 0.0;
        $total = $subtotal;
        if ($request->filled('coupon_code')) {
            $coupon = Coupon::where('code', strtoupper(trim($request->coupon_code)))->first();
            if ($coupon && $coupon->isValid($subtotal)) {
                $discount = $coupon->discountFor($subtotal);
                $total = max(0, $subtotal - $discount);
            }
        }

        $phoneVerified = false;
        if ($user->phone) {
            $phoneVerified = Verification::where('phone', $user->phone)
                ->where('verified', true)
                ->where('expires_at', '>', now())
                ->exists();
        }

        return view('checkout', compact('cartItems', 'subtotal', 'coupon', 'discount', 'total', 'needsAddress', 'phoneVerified'));
    }

    /**
     * Store/Create a new order from the user's cart
     * 
     * @param Request $request
     * @return Redirect
     * 
     * Process:
     * 1. Validate cart is not empty
     * 2. Validate all stock availability again
     * 3. Calculate totals with coupon discount
     * 4. Begin database transaction
     * 5. Create order record
     * 6. Create order items (line items)
     * 7. Decrement product stock
     * 8. Increment coupon usage count
     * 9. Clear user's cart
     * 10. Send confirmation email
     * 11. Redirect to orders list
     * 
     * Database Transaction:
     * - All operations grouped together
     * - If any step fails, all changes rolled back
     * - Prevents partial orders
     */
    public function store(Request $request, \App\Services\TwilioService $twilio)
    {
        $user = Auth::user();
        // Refresh user from DB to pick up any phone updates performed by the verification flow
        try {
            $user->refresh();
        } catch (\Throwable $e) {
            // ignore refresh failures
        }
        Log::info('OrderController@store called', ['user_id' => $user?->id, 'phone' => $user?->phone]);
        $cartItems = $user->cartItems()->with('product')->get();

        // Normalize transaction_id if JS/form submitted it as an array by mistake
        if ($request->has('transaction_id') && is_array($request->input('transaction_id'))) {
            $first = $request->input('transaction_id')[0] ?? null;
            $request->merge(['transaction_id' => $first]);
            Log::warning('transaction_id received as array; coerced to string', ['user_id' => $user->id ?? null, 'transaction_id_first' => $first]);
        }

        // Enforce phone verification before allowing checkout.
        // Accept any of:
        // - a verified `Verification` record for the user's phone
        // - the user's `phone_verified_at` timestamp
        // - a recent session flag set during verification flows (`otp_verified_for`)
        $phone = $user->phone;
        $sessionVerifiedPhone = session('otp_verified_for');
        $hasVerifiedPhone = false;

        // If session marks this phone as verified, trust it
        if (! empty($sessionVerifiedPhone) && $sessionVerifiedPhone === $phone) {
            $hasVerifiedPhone = true;
        }

        // Check user's persistent flag
        if (! $hasVerifiedPhone && ! empty($user->phone_verified_at)) {
            $hasVerifiedPhone = true;
        }

        // Check verification records (some verification flows create verified records)
        if (! $hasVerifiedPhone && ! empty($phone)) {
            $hasVerifiedPhone = \App\Models\Verification::where('phone', $phone)
                ->where(function ($q) {
                    $q->where('verified', true)->orWhereNull('code');
                })
                ->where('expires_at', '>', now())
                ->exists();
        }

        if (! $hasVerifiedPhone) {
            return redirect()->back()->withInput()->with('error', 'Please verify your phone number again before checkout.');
        }
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $request->validate([
            'notes' => 'nullable|string|max:500',
            'coupon_code' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:100',
            'street_name' => 'nullable|string|max:191',
            'building_name' => 'nullable|string|max:191',
            'floor_apartment' => 'nullable|string|max:100',
            'landmark' => 'nullable|string|max:191',
            'city_area' => 'nullable|string|max:191',
            'phone' => 'nullable|string|max:30',
            'payment_method' => 'required|in:bankak,cod',
            'receipt' => 'required_if:payment_method,bankak|image|max:4096',
            'transaction_id' => ['required_if:payment_method,bankak', 'nullable', 'string', 'max:255', \Illuminate\Validation\Rule::unique('orders', 'transaction_id')],
        ]);

        // If address fields were submitted, persist them to the user's profile
        $addressFields = $request->only(['country', 'street_name', 'building_name', 'floor_apartment', 'landmark', 'city_area', 'phone']);
        $addressProvided = collect($addressFields)->filter(fn($v) => filled($v))->isNotEmpty();
        if ($addressProvided) {
            $user->fill($addressFields);
            $user->save();
        }

        // Defensive: require a shipping address before creating the order
        if (method_exists($user, 'hasShippingAddress') && ! $user->hasShippingAddress()) {
            return redirect()->back()->withInput()->with('error', 'Shipping Address is required to place an order.');
        }

        // Payment-specific processing
        $paymentMethod = $request->payment_method;
        $transactionId = null;
        $receiptPath = null;

        if ($paymentMethod === 'bankak') {
            // store receipt
            if ($request->hasFile('receipt')) {
                try {
                    $disk = config('filesystems.default', 'public');
                    // If default disk is cloudinary (or explicitly set), attempt to upload there
                    if ($disk === 'cloudinary') {
                        // putFile returns the stored path (public id)
                        $stored = Storage::disk('cloudinary')->putFile('receipts', $request->file('receipt'));
                        // Try to resolve a secure URL; Storage::url should work for Cloudinary driver
                        try {
                            $receiptPath = Storage::disk('cloudinary')->url($stored);
                        } catch (\Throwable $e) {
                            // Fallback: use CloudinaryHelper to build URL from returned path
                            $receiptPath = \App\Helpers\CloudinaryHelper::getUrl($stored);
                        }
                    } else {
                        $receiptPath = $request->file('receipt')->store('receipts', $disk);
                    }
                } catch (\Throwable $e) {
                    report($e);
                    // fallback to local public storage to avoid blocking order creation
                    try {
                        $receiptPath = $request->file('receipt')->store('receipts', 'public');
                    } catch (\Throwable $inner) {
                        report($inner);
                        $receiptPath = null;
                    }
                }
            }
            $transactionId = $request->transaction_id;
            // Check for existing order with same transaction id to avoid unique constraint errors.
            if ($transactionId) {
                $existing = Order::where('transaction_id', $transactionId)->first();
                if ($existing) {
                    // If the existing order belongs to the same user, redirect them to that order.
                    if ($existing->user_id === $user->id) {
                        return redirect()->route('orders.show', $existing->id)->with('success', 'An order with this transaction id already exists.');
                    }

                    // Otherwise reject — transaction id in use by another account.
                    return redirect()->back()->withInput()->with('error', 'The transaction id has already been used. If this is your payment, contact support.');
                }
            }
            $paymentStatus = 'awaiting_admin_approval';
        } elseif ($paymentMethod === 'cod') {
            // COD only available for Port Sudan addresses
            $city = strtolower(trim($user->city_area ?? ''));
            if (strpos($city, 'port') === false && strpos($city, 'port sudan') === false && strpos($city, 'portsudan') === false) {
                return redirect()->back()->withInput()->with('error', 'Cash on Delivery is only available for Port Sudan addresses.');
            }

            // require phone verification (reuse computed flag)
            if (! $hasVerifiedPhone) {
                return redirect()->back()->withInput()->with('error', 'Please verify your phone number via OTP before placing a COD order.');
            }

            $paymentStatus = 'pending_delivery';
        } else {
            $paymentStatus = 'pending';
        }

        // Validate stock again (prevents race conditions)
        foreach ($cartItems as $item) {
            if ($item->product->quantity < $item->quantity) {
                return redirect()->route('cart.index')->with('error', "Not enough stock for: {$item->product->name}. Available: {$item->product->quantity}");
            }
        }

        $shippingAddress = $user->formatShippingAddress();
        $phone = $user->phone;
        $subtotal = $cartItems->sum(fn ($i) => $i->product->price * $i->quantity);
        $coupon = null;
        $discount = 0.0;
        if ($request->filled('coupon_code')) {
            $coupon = Coupon::where('code', strtoupper(trim($request->coupon_code)))->first();
            if ($coupon && $coupon->isValid($subtotal)) {
                $discount = $coupon->discountFor($subtotal);
            }
        }
        $total = max(0, $subtotal - $discount);

        $order = null;
        DB::transaction(function () use ($user, $cartItems, $shippingAddress, $phone, $request, $coupon, $discount, $total, $paymentMethod, $paymentStatus, $transactionId, $receiptPath, &$order) {
            $orderSubtotal = 0;
            // Create the order with payment metadata
            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'payment_method' => $paymentMethod,
                'payment_status' => $paymentStatus,
                'transaction_id' => $transactionId,
                'receipt_path' => $receiptPath,
                'total' => $total,
                'shipping_address' => $shippingAddress,
                'phone' => $phone,
                'notes' => $request->notes,
                'coupon_id' => $coupon?->id,
                'discount' => $discount,
            ]);

            // Create order items from cart items
            foreach ($cartItems as $item) {
                $itemSubtotal = $item->product->price * $item->quantity;
                $orderSubtotal += $itemSubtotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'price' => $item->product->price,
                    'quantity' => $item->quantity,
                    'subtotal' => $itemSubtotal,
                ]);

                $item->product->decrement('quantity', $item->quantity);
            }

            if ($coupon) {
                $coupon->increment('used_count');
            }

            $user->cartItems()->delete();
        });

        // Log order payment method for admin visibility and debugging
        try {
            Log::info('Order created', ['order_id' => $order->id ?? null, 'payment_method' => $order->payment_method ?? $paymentMethod]);
        } catch (\Throwable $e) {
            // don't break order flow if logging fails
            report($e);
        }

        // Defensive: ensure payment_method persisted. Some environments/migrations
        // or DB drivers might ignore unknown keys during create; enforce now.
        try {
            if ($order && empty($order->payment_method) && ! empty($paymentMethod)) {
                $order->payment_method = $paymentMethod;
                $order->payment_status = $order->payment_status ?? $paymentStatus ?? 'pending';
                $order->save();
                Log::info('Order payment_method enforced after create', ['order_id' => $order->id, 'payment_method' => $order->payment_method]);
            }
        } catch (\Throwable $e) {
            report($e);
        }

        // Queue email so the request returns immediately. Prevents 502 on Railway.
        // On Railway: set QUEUE_CONNECTION=database and run a worker (see RAILWAY_DEPLOY.md).
        // If QUEUE_CONNECTION=sync, this still runs in-request and can timeout.
        try {
            Mail::to($user->email)->queue(new OrderConfirmed($order->fresh(['items'])));
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()->route('orders.index')->with('success', 'Order placed successfully! Confirmation email sent.');
    }


    /**
     * List all orders for the authenticated user
     * 
     * @return \Illuminate\View\View
     * 
     * Features:
     * - Pagination (10 items per page)
     * - Eager loads order relationships
     * - Only shows user's own orders (Auth::user()->orders())
     */
    public function index()
    {
        $orders = Auth::user()->orders()->with('items.product')->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    /**
     * Show detailed view of a specific order
     * 
     * @param Order $order
     * @return \Illuminate\View\View
     * 
     * Security:
     * - Verifies user owns the order OR is admin
     * - IDOR protection: user_id !== Auth::id() && !is_admin → abort(403)
     * - Returns 403 Forbidden if unauthorized
     * 
     * Relationships loaded:
     * - Order items with product images
     * - Ensures complete order data available
     */
    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403);
        }

        $order->load('items.product.images');
        return view('orders.show', compact('order'));
    }

    /**
     * Admin-only: Display all orders with statistics
     * 
     * @return \Illuminate\View\View
     * 
     * Statistics calculated:
     * - Total orders count
     * - Total revenue (shipped/delivered only)
     * - Pending orders count
     * - Completed orders count
     * - Orders grouped by status
     * - Revenue data for last 7 days (for graphs)
     * 
     * Authorization:
     * - Protected by middleware: auth, admin
     * - Check auth()->user()->is_admin == 1
     * 
     * Performance:
     * - Uses eager loading (.with) to prevent N+1 queries
     * - Pagination (15 items per page)
     * - Database aggregations (COUNT, SUM) for stats
     */
    public function adminIndex()
    {
        $orders = Order::with('user', 'items')->latest()->paginate(15);
        
        // Order statistics
        $totalOrders = Order::count();
        $totalRevenue = Order::whereIn('status', ['delivered', 'shipped'])->sum('total');
        $pendingOrders = Order::where('status', 'pending')->count();
        $completedOrders = Order::where('status', 'delivered')->count();
        
        // Orders by status
        $ordersByStatus = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();
        
        // Revenue data (last 7 days)
        $last7Days = Order::selectRaw('DATE(created_at) as date, SUM(total) as revenue')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        return view('admin.orders.index', compact(
            'orders',
            'totalOrders',
            'totalRevenue',
            'pendingOrders',
            'completedOrders',
            'ordersByStatus',
            'last7Days'
        ));
    }

    /**
     * Admin-only: Update order status
     * 
     * @param Request $request - Must contain 'status' field
     * @param Order $order - Order to update
     * @return \Illuminate\Http\RedirectResponse
     * 
     * Valid statuses:
     * - pending: Order received, awaiting processing
     * - processing: Order being packed/prepared
     * - shipped: Order in transit
     * - delivered: Order received by customer
     * - cancelled: Order cancelled (refund issued)
     * 
     * Authorization:
     * - Admin only (protected by middleware)
     * 
     * Future enhancements:
     * - Send email notification to customer when status changes
     * - Log status change history
     * - No downgrade allowed (can't go from delivered → shipped)
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $newStatus = $request->status;

        // When marking delivered, if not already set, record payment received amount (admin/delivery signs)
        if ($newStatus === 'delivered') {
            $updates = ['status' => 'delivered'];

            if (is_null($order->payment_received_amount)) {
                // Assign the money value to payment_received_amount when delivered
                $updates['payment_received_amount'] = $order->total;
                // mark payment_status as paid if not already
                $updates['payment_status'] = $order->payment_status ?? 'paid';
            }

            $order->update($updates);
        } else {
            $order->update(['status' => $newStatus]);

            // If order moved to shipped, notify the customer
            if ($newStatus === 'shipped') {
                try {
                    Mail::to($order->user->email)->queue(new OrderShipped($order->fresh(['items', 'user'])));
                } catch (\Throwable $e) {
                    report($e);
                }
            }
        }

        return back()->with('success', 'Order status updated.');
    }

    /**
     * Admin approves a Bankak payment after manual verification.
     */
    public function approvePayment(Request $request, Order $order)
    {
        // Only admin middleware route should reach here
        if ($order->payment_method !== 'bankak') {
            return back()->with('error', 'This order is not a Bankak payment.');
        }

        $order->update([
            'payment_status' => 'verified',
            'status' => 'processing',
        ]);

        // Generate PDF invoice and attach to approval email (if dompdf available)
        $pdfData = null;
        $filename = 'invoice-' . $order->id . '.pdf';
        try {
            if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
                $fresh = $order->fresh(['items.product', 'user']);
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.order-invoice', ['order' => $fresh]);
                $pdfData = $pdf->output();
                // Base64-encode the PDF so it remains valid UTF-8 when passed through transports/logging
                $pdfData = base64_encode($pdfData);
            }
        } catch (\Throwable $e) {
            report($e);
            $pdfData = null;
        }

        // Send payment approved email with invoice attached when available
        try {
            // Attempt to send immediately (helps debug delivery issues when queue driver is sync)
            Mail::to($order->user->email)->send(new \App\Mail\PaymentApproved($order->fresh(['items', 'user']), $pdfData, $filename));
            \Log::info('PaymentApproved email sent (attempted) for order ' . $order->id, ['email' => $order->user->email]);
        } catch (\Throwable $e) {
            report($e);
            \Log::error('Failed sending PaymentApproved email for order ' . $order->id, ['exception' => $e->getMessage()]);
        }

        return back()->with('success', 'Payment verified and order moved to processing.');
    }

    /**
     * Show payment edit form for customers via signed link.
     */
    public function editPayment(Request $request, Order $order)
    {
        if (! $request->hasValidSignature()) {
            abort(403);
        }

        // Only allow editing when payment was rejected
        if ($order->payment_status !== 'failed') {
            return redirect('/')->with('error', 'This payment cannot be edited.');
        }

        // Create a signed post URL for the update action with the same expiry
        $postUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute('orders.payment.update', now()->addHours(72), ['order' => $order->id]);

        return view('orders.payment-edit', compact('order', 'postUrl'));
    }

    /**
     * Handle the posted payment update from the secure link.
     */
    public function updatePayment(Request $request, Order $order)
    {
        if (! $request->hasValidSignature()) {
            abort(403);
        }

        if ($order->payment_status !== 'failed') {
            return redirect('/')->with('error', 'This payment cannot be updated.');
        }

        $data = $request->validate([
            'transaction_id' => 'required|string|max:255',
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'note' => 'nullable|string|max:1000',
        ]);

        // Handle receipt upload
        if ($request->hasFile('receipt')) {
            try {
                $path = $request->file('receipt')->store('receipts');
            } catch (\Throwable $e) {
                return back()->with('error', 'Failed to store receipt. Please try again.');
            }
            $order->receipt_path = $path;
        }

        // Update transaction id and set payment_status back to awaiting admin review
        $order->transaction_id = $data['transaction_id'];
        $order->payment_status = 'awaiting_admin_approval';
        $order->save();

        // Optionally notify admin here (left to ops)

        // Redirect to the signed edit (GET) page to avoid issuing a GET to the POST-only update route
        try {
            $editUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute('orders.payment.edit', now()->addHours(72), ['order' => $order->id]);
        } catch (\Throwable $e) {
            $editUrl = url('/');
        }

        return redirect($editUrl)->with('success', 'Payment update submitted. Our team will review it shortly.');
    }

    /**
     * Admin rejects a Bankak payment.
     */
    public function rejectPayment(Request $request, Order $order)
    {
        if ($order->payment_method !== 'bankak') {
            return back()->with('error', 'This order is not a Bankak payment.');
        }

        $data = $request->validate([
            'reason' => 'nullable|string|max:1000',
        ]);

        $reason = $data['reason'] ?? null;

        $updates = [
            'payment_status' => 'failed',
            'status' => 'cancelled',
        ];

        // Persist rejection_reason only if the column exists (avoid migration mismatch errors)
        try {
            if (\Schema::hasColumn('orders', 'rejection_reason')) {
                $updates['rejection_reason'] = $reason;
            }
        } catch (\Throwable $e) {
            // If Schema is not accessible for some reason, skip adding the column
        }

        $order->update($updates);

        // Build a temporary signed edit link so customer can securely resubmit payment details
        try {
            $editUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute(
                'orders.payment.edit', now()->addHours(72), ['order' => $order->id]
            );
        } catch (\Throwable $e) {
            $editUrl = null;
        }

        // Send payment rejected email including the reason and edit link
        try {
            Mail::to($order->user->email)->queue(new \App\Mail\PaymentRejected($order->fresh(['items', 'user']), $reason, $editUrl));
        } catch (\Throwable $e) {
            report($e);
        }

        return back()->with('success', 'Payment rejected and order cancelled.');
    }
}
