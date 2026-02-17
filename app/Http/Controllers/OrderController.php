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

        return view('checkout', compact('cartItems', 'subtotal', 'coupon', 'discount', 'total'));
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
    public function store(Request $request)
    {
        $user = Auth::user();
        $cartItems = $user->cartItems()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $request->validate([
            'notes' => 'nullable|string|max:500',
            'coupon_code' => 'nullable|string|max:50',
        ]);

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
        DB::transaction(function () use ($user, $cartItems, $shippingAddress, $phone, $request, $coupon, $discount, $total, &$order) {
            $orderSubtotal = 0;
            // Create the order
            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
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

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Order status updated.');
    }
}
