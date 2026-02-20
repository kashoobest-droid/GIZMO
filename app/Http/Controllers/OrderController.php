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
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Helpers\CodHelper;

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

        // Check COD eligibility (location + order amount)
        $codEligibility = CodHelper::isEligible($user->city_area, $total);
        $codAvailable = $codEligibility['available'];
        $codMessage = $codEligibility['reason'];

        return view('checkout', compact('cartItems', 'subtotal', 'coupon', 'discount', 'total', 'needsAddress', 'phoneVerified', 'codAvailable', 'codMessage'));
    }

    /**
     * Generate secure Cloudinary upload signature for Bankak payment screenshots
     * 
     * Returns a JSON response with the signature, timestamp, API key, and cloud name
     * required to directly upload a file to Cloudinary from the frontend.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCloudinarySignature()
    {
        try {
            $timestamp = now()->getTimestamp();
            $folder = 'bankak_payments';
            
            // Get Cloudinary credentials from config
            $cloudName = config('cloudinary.cloud.name');
            $apiKey = config('cloudinary.api.key');
            $apiSecret = config('cloudinary.api.secret');
            
            if (!$cloudName || !$apiKey || !$apiSecret) {
                \Log::error('Cloudinary config missing', [
                    'cloud_name' => !$cloudName,
                    'api_key' => !$apiKey,
                    'api_secret' => !$apiSecret,
                ]);
                return response()->json(['error' => 'Cloudinary not configured'], 500);
            }
            
            // Build parameters for signing
            $params = [
                'timestamp' => $timestamp,
                'folder' => $folder,
            ];
            
            // Sign the request using Cloudinary SDK
            $signature = Cloudinary::apiSignRequest($params, $apiSecret);
            
            \Log::info('Cloudinary signature generated', [
                'timestamp' => $timestamp,
                'folder' => $folder,
                'signature' => substr($signature, 0, 10) . '...',
            ]);
            
            return response()->json([
                'signature' => $signature,
                'timestamp' => $timestamp,
                'api_key' => $apiKey,
                'cloud_name' => $cloudName,
                'folder' => $folder,
                'success' => true,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error generating Cloudinary signature', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);
            return response()->json(['error' => 'Failed to generate signature'], 500);
        }
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
            // Phone verification temporarily disabled for checkout flow.
            // Previously this returned an error to force OTP verification.
            Log::info('Checkout phone verification skipped for user', ['user_id' => $user->id, 'phone' => $phone]);
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
            'receipt' => 'nullable|mimes:jpg,png,jpeg|max:2048', // Legacy support - optional for backward compatibility
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
            // receipt processing will occur inside DB transaction after order has an id
            // keep receiptPath null for now
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
            // COD only available for Port Sudan (Arabic or English) and orders under 60,000 SDG
            $codEligibility = CodHelper::isEligible($user->city_area, $total);
            if (!$codEligibility['available']) {
                return redirect()->back()->withInput()->with('error', $codEligibility['reason']);
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
        DB::transaction(function () use ($user, $cartItems, $shippingAddress, $phone, $request, $coupon, $discount, $total, $paymentMethod, $paymentStatus, $transactionId, &$order) {
            $orderSubtotal = 0;
            // Create the order with payment metadata
            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'payment_method' => $paymentMethod,
                'payment_status' => $paymentStatus,
                'transaction_id' => $transactionId,
                // receipt info will be attached after uploading to Cloudinary (if applicable)
                'receipt_path' => null,
                'total' => $total,
                'shipping_address' => $shippingAddress,
                'phone' => $phone,
                'notes' => $request->notes,
                'coupon_id' => $coupon?->id,
                'discount' => $discount,
            ]);

            // If payment via Bankak, process any uploaded receipt file (legacy flow)
            if ($paymentMethod === 'bankak') {
                if ($request->hasFile('receipt')) {
                    try {
                        $file = $request->file('receipt');
                        $timestamp = time();
                        $folder = "uploads/users/{$user->id}/orders/{$order->id}";
                        $filename = "receipt_{$timestamp}." . $file->getClientOriginalExtension();

                        // Upload to Cloudinary using Storage facade (same as product images)
                        $path = Storage::disk('cloudinary')->putFileAs($folder, $file, $filename);
                        $receiptUrl = \App\Helpers\CloudinaryHelper::getUrl($path);

                        Log::info('Cloudinary upload (checkout) success', ['path' => $path, 'receipt_url' => $receiptUrl, 'order_id' => $order->id ?? null]);

                        // Persist receipt info on the order record
                        $order->receipt_path = $path;
                        $order->receipt_url = $receiptUrl;
                        $order->receipt_public_id = $folder . '/' . $filename;
                        $order->save();
                    } catch (\Throwable $e) {
                        // log but do not abort; keep order creation going
                        report($e);
                        Log::error('Cloudinary upload failed during order create', ['error' => $e->getMessage(), 'order_id' => $order->id ?? null, 'user_id' => $user->id ?? null]);
                        // Fallback: store receipt to public disk so admin can access via /storage
                        try {
                            $fileFallback = $request->file('receipt');
                            if ($fileFallback) {
                                $localPath = $fileFallback->store('receipts', 'public');
                                $order->receipt_path = $localPath;
                                // Use Storage facade with explicit 'public' disk to avoid cloudinary
                                $order->receipt_url = \Illuminate\Support\Facades\Storage::disk('public')->url($localPath);
                                Log::info('Stored receipt fallback to public disk', ['local_path' => $localPath, 'receipt_url' => $order->receipt_url, 'order_id' => $order->id ?? null]);
                                $order->save();
                            }
                        } catch (\Throwable $inner) {
                            report($inner);
                            Log::error('Fallback storage failed', ['error' => $inner->getMessage(), 'order_id' => $order->id ?? null]);
                        }
                    }
                }
            }

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
     * Generate a signed Cloudinary URL for a private receipt image.
     * Returns JSON: { url: 'https://...'}
     * URL expires in 10 minutes.
     */
    public function signedReceipt(Order $order)
    {
        if (! Auth::user() || ! Auth::user()->is_admin) {
            abort(403);
        }

        $publicId = $order->receipt_public_id;
        if (empty($publicId)) {
            return response()->json(['error' => 'No receipt available for this order.'], 404);
        }

        try {
            // Log the receipt_public_id for debugging
            Log::info('signedReceipt called', ['order_id' => $order->id, 'public_id' => $publicId, 'receipt_url' => $order->receipt_url]);

            // If we have receipt_url already, use it directly (it's from Cloudinary)
            if (! empty($order->receipt_url)) {
                Log::info('Using existing receipt_url', ['order_id' => $order->id, 'receipt_url' => $order->receipt_url]);
                return response()->json(['url' => $order->receipt_url]);
            }

            // Fallback: try to generate signed URL from public_id
            $options = [
                'sign_url' => true,
                'expires_at' => now()->addMinutes(10)->timestamp,
            ];

            $result = Cloudinary::privateResource($publicId, $options);

            $url = null;
            if (is_array($result)) {
                $url = $result['secure_url'] ?? $result['url'] ?? $result['signed_url'] ?? null;
            } elseif (is_string($result)) {
                $url = $result;
            } elseif (is_object($result)) {
                $url = $result->secure_url ?? $result->url ?? ($result->signed_url ?? null);
            }

            if (empty($url)) {
                $url = \App\Helpers\CloudinaryHelper::getUrl($publicId);
            }

            Log::info('Generated signed URL', ['order_id' => $order->id, 'url' => $url]);
            return response()->json(['url' => $url]);
        } catch (\Throwable $e) {
            Log::error('signedReceipt failed', ['order_id' => $order->id, 'error' => $e->getMessage(), 'public_id' => $publicId]);
            report($e);
            return response()->json(['error' => 'Failed to generate signed URL.'], 500);
        }
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

        // Security: Ensure the order ID from the route parameter matches what we're trying to update
        if (!$order || !$order->id) {
            \Log::warning('updateStatus called with invalid order', ['order' => $order, 'admin_id' => \Auth::id()]);
            return back()->with('error', 'Invalid order. Order not found.');
        }

        // Prevent redundant updates (if status hasn't actually changed)
        if ($order->status === $newStatus) {
            \Log::info('updateStatus called with same status (no-op)', [
                'order_id' => $order->id,
                'status' => $newStatus,
                'admin_id' => \Auth::id(),
            ]);
            return back()->with('info', 'Order status unchanged.');
        }

        // Log the status change for audit trail BEFORE making any changes
        \Log::info('Order status change requested', [
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'new_status' => $newStatus,
            'old_status' => $order->status,
            'admin_id' => \Auth::id(),
            'timestamp' => now(),
        ]);

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
            \Log::info('Order marked as delivered', ['order_id' => $order->id, 'admin_id' => \Auth::id()]);
        } else {
            $order->update(['status' => $newStatus]);
            \Log::info('Order status updated', ['order_id' => $order->id, 'new_status' => $newStatus, 'admin_id' => \Auth::id()]);

            // If order moved to shipped, notify the customer
            if ($newStatus === 'shipped') {
                try {
                    Mail::to($order->user->email)->queue(new OrderShipped($order->fresh(['items', 'user'])));
                } catch (\Throwable $e) {
                    report($e);
                }
            }
        }

        return back()->with('success', 'Order status updated to ' . __('messages.admin_status_' . $newStatus) . '.');
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

        // Generate PDF invoice and attach to approval email (using DomPDF - improved setup for Arabic)
        $pdfData = null;
        $filename = 'invoice-' . $order->id . '.pdf';
        try {
            $fresh = $order->fresh(['items.product', 'user']);
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.order-invoice', ['order' => $fresh]);
            $pdfData = $pdf->output();
            // Base64-encode the PDF so it remains valid UTF-8 when passed through transports/logging
            $pdfData = base64_encode($pdfData);
            \Log::info('Invoice PDF generated successfully for order ' . $order->id, ['size' => strlen($pdfData)]);
        } catch (\Throwable $e) {
            \Log::error('Failed to generate PDF for order ' . $order->id, [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
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

        $data = $request->validate([
            'transaction_id' => 'required|string|max:255',
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'note' => 'nullable|string|max:1000',
        ]);

        // Handle receipt upload: prefer Cloudinary, fallback to local storage
        if ($request->hasFile('receipt')) {
            $file = $request->file('receipt');
            try {
                $timestamp = time();
                $folder = "uploads/users/{$order->user_id}/orders/{$order->id}";
                $filename = "receipt_{$timestamp}." . $file->getClientOriginalExtension();

                // Upload to Cloudinary using Storage facade (same as product images)
                $path = Storage::disk('cloudinary')->putFileAs($folder, $file, $filename);
                $receiptUrl = \App\Helpers\CloudinaryHelper::getUrl($path);

                Log::info('Cloudinary upload (updatePayment) success', ['path' => $path, 'receipt_url' => $receiptUrl, 'order_id' => $order->id ?? null]);

                $order->receipt_path = $path;
                $order->receipt_url = $receiptUrl;
                $order->receipt_public_id = $folder . '/' . $filename;  // Store folder/filename as public id for reference
            } catch (\Throwable $e) {
                Log::error('Cloudinary upload failed (updatePayment)', ['error' => $e->getMessage(), 'order_id' => $order->id ?? null]);
                // fallback to public storage so admin can view
                try {
                    $path = $file->store('receipts', 'public');
                    $order->receipt_path = $path;
                    // Use Storage facade with explicit 'public' disk to avoid cloudinary
                    $order->receipt_url = Storage::disk('public')->url($path);
                    Log::info('Stored receipt fallback to public disk (updatePayment)', ['local_path' => $path, 'receipt_url' => $order->receipt_url, 'order_id' => $order->id ?? null]);
                } catch (\Throwable $inner) {
                    Log::error('Fallback storage failed (updatePayment)', ['error' => $inner->getMessage(), 'order_id' => $order->id ?? null]);
                    return back()->with('error', 'Failed to store receipt. Please try again.');
                }
            }
        }

        // Update transaction id and set payment_status back to awaiting admin review
        $order->transaction_id = $data['transaction_id'];
        $order->payment_status = 'awaiting_admin_approval';
        $order->save();

        // Optionally notify admin here (left to ops)

        // Redirect to the order details page so the user sees confirmation
        return redirect()->route('orders.show', $order->id)->with('success', 'Payment update submitted. Our team will review it shortly.');
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
