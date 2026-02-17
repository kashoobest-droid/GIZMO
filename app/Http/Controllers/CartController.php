<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * CartController - Manage user shopping carts
 * 
 * Handles cart operations: view, add, remove, update quantities
 * 
 * Methods:
 * - index(): Display cart contents
 * - add(): Add product to cart (with quantity validation)
 * - remove(): Remove item from cart (with ownership check)
 * - updateQuantity(): Change quantity (respects stock limit)
 * 
 * Security:
 * - All operations check Auth::id() vs cartItem.user_id
 * - Prevents users from accessing other users' carts
 * - Quantity bounded by product stock
 * 
 * Data structure:
 * - CartItem table: user_id, product_id, quantity
 * - Route Model Binding used for CartItem
 * - Relationships: User → CartItems → Products
 * 
 * API vs HTML:
 * - All methods support both HTML and JSON responses
 * - Use response()->json() if request expects JSON
 * - Otherwise redirect with message
 * 
 * Stock validation:
 * - add(): Checks product.quantity > 0
 * - updateQuantity(): Caps at product.quantity
 * - Min 1, Max product.quantity
 */
class CartController extends Controller
{
    /**
     * Display user's shopping cart
     * 
     * @return \Illuminate\View\View - cart view with items
     * 
     * Data loaded:
     * - Auth::user()->cartItems() with eager loading
     * - Each cartItem includes:
     *   - product.images (for display)
     *   - product.category (for breadcrumbs)
     * 
     * Calculated:
     * - cartCount: Total items in cart (sum of quantities)
     * 
     * View features:
     * - List of items with images, name, price
     * - Quantity selector for each item
     * - Remove button for each item
     * - Subtotal and total calculations
     * - Proceed to checkout button
     * - Empty cart message if no items
     * 
     * Performance:
     * - Eager loads to prevent N+1 queries with images/category
     */
    public function index()
    {
        $cartItems = Auth::user()->cartItems()->with('product.images', 'product.category')->get();
        $cartCount = $cartItems->sum('quantity');
        return view('cart', compact('cartItems', 'cartCount'));
    }

    /**
     * Add product to cart with stock validation
     * 
     * @param products $product - Product being added (route model binding)
     * @param Request $request - Contains: quantity (optional, default 1)
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     * 
     * Stock validation:
     * - Checks product.quantity > 0
     * - Returns error if out of stock
     * - Message: "This product is out of stock."
     * 
     * Quantity handling:
     * - Defaults to 1 if not provided
     * - Validates: min 1, max product.quantity
     * - Formula: max(1, min(requested, available))
     * - Prevents 0 and over-ordering
     * 
     * Database operation:
     * - Uses firstOrNew pattern (find or create)
     * - Looks up by: user_id + product_id
     * - If item exists → add to quantity
     * - If new → create with requested quantity
     * - Prevents duplicate cart entries
     * 
     * Response:
     * - HTML: Redirect with "Product added to cart!" message
     * - JSON: {"success": true, "message": "Product added to cart!"}
     * 
     * Auto-response format detection:
     * - AJAX calls (request.expectsJson()) → JSON response
     * - Form submissions → HTML redirect
     * 
     * Example:
     * - User adds 2x quantity of product_id=5
     * - If not in cart → cartItem.quantity = 2
     * - If already 3 in cart → cartItem.quantity = 5 (3+2)
     */
    public function add(products $product, Request $request)
    {
        $available = $product->quantity ?? 0;
        if ($available < 1) {
            return back()->with('error', 'This product is out of stock.');
        }

        $quantity = $request->input('quantity', 1);
        $quantity = max(1, min((int) $quantity, $available));

        $item = CartItem::firstOrNew([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
        ]);

        $item->quantity = $item->exists ? $item->quantity + $quantity : $quantity;
        $item->save();

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Product added to cart!']);
        }

        return back()->with('success', 'Product added to cart!');
    }

    /**
     * Remove item from user's cart
     * 
     * @param CartItem $cartItem - Item to remove (route model binding)
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse - Confirmation with message
     * 
     * Authorization:
     * - Checks cartItem.user_id === Auth::id()
     * - Throws 403 Forbidden if not owner
     * - Prevents users from deleting others' carts
     * 
     * Operation:
     * - Permanently deletes CartItem record
     * - User can re-add easily if mistake
     * - No audit trail (permanent removal)
     * 
     * Response:
     * - HTML: Back with "Item removed from cart." message
     * - JSON: {"success": true}
     * 
     * Auto-response format detection:
     * - AJAX calls (request.expectsJson()) → JSON response
     * - Form submissions → HTML redirect
     * 
     * Side effects:
     * - Cart count decreases
     * - Item disappears from cart view
     */
    public function remove(CartItem $cartItem)
    {
        if ($cartItem->user_id !== Auth::id()) {
            abort(403);
        }
        $cartItem->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Item removed from cart.');
    }

    /**
     * Update quantity of item in cart
     * 
     * @param CartItem $cartItem - Item to update (route model binding)
     * @param Request $request - Contains: quantity
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse - Success response
     * 
     * Authorization:
     * - Checks cartItem.user_id === Auth::id()
     * - Throws 403 Forbidden if not owner
     * - Prevents unauthorized quantity changes
     * 
     * Quantity validation:
     * - Defaults to 1 if not provided
     * - Minimum: 1 (prevent 0 quantity)
     * - Maximum: product.quantity (stock limit)
     * - Formula: max(1, min(requested, available))
     * 
     * Database operation:
     * - Updates cartItem.quantity
     * - No transaction needed (single update)
     * 
     * Response:
     * - HTML: Back with implicit success
     * - JSON: {"success": true, "quantity": newQuantity}
     * 
     * Auto-response format detection:
     * - AJAX calls → JSON (includes updated quantity for confirmation)
     * - Form submissions → HTML redirect
     * 
     * Example:
     * - Item quantity = 5, product has 10 available
     * - User enters quantity = 15 → updates to 10 (capped)
     * - User enters quantity = 0 → updates to 1 (minimum)
     * - User enters quantity = 3 → updates to 3 (valid)
     */
    public function updateQuantity(CartItem $cartItem, Request $request)
    {
        if ($cartItem->user_id !== Auth::id()) {
            abort(403);
        }

        $quantity = max(1, min((int) $request->input('quantity', 1), $cartItem->product->quantity ?? 999));
        $cartItem->update(['quantity' => $quantity]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'quantity' => $quantity]);
        }

        return back();
    }
}
