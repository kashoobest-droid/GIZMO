<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * FavoriteController - Manage user wishlists/favorites
 * 
 * Handles favoriting products: view, add, remove, toggle
 * 
 * Methods:
 * - index(): Display all favorited products
 * - add(): Add product to favorites (no-op if already favorited)
 * - remove(): Remove product from favorites
 * - toggle(): Add if not favorite, or remove if already favorite
 * 
 * Security:
 * - All operations tied to Auth::id()
 * - No cross-user access possible
 * - Auto-protected by business logic
 * 
 * Data structure:
 * - Favorite table: user_id, product_id, timestamps
 * - One-to-many: User → Favorites → Products
 * - Implicit constraint: unique(user_id, product_id)
 * 
 * API vs HTML:
 * - All methods support both HTML and JSON responses
 * - Auto-detect based on request().expectsJson()
 * 
 * Use cases:
 * - Wishlist feature (save for later)
 * - Tracking product interest
 * - Personalization for recommendations (future)
 */
class FavoriteController extends Controller
{
    /**
     * Display user's favorite products
     * 
     * @return \Illuminate\View\View - favorites view
     * 
     * Data loaded:
     * - Auth::user()->favorites() with product.images, product.category
     * - All favorited products with full details
     * 
     * Additional:
     * - cartCount: For header/navbar display
     * 
     * View features:
     * - Grid of bookmarked products
     * - Product images, names, prices
     * - Remove from favorites button
     * - Add to cart button
     * - Empty state message if no favorites
     * 
     * Performance:
     * - Eager loads to prevent N+1 with images
     */
    public function index()
    {
        $favorites = Auth::user()->favorites()->with('product.images', 'product.category')->get();
        $cartCount = Auth::user()->cartItems()->sum('quantity');
        return view('favorites', compact('favorites', 'cartCount'));
    }

    /**
     * Add product to favorites (no-op if already favorite)
     * 
     * @param products $product - Product to favorite (route model binding)
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     * 
     * Operation:
     * - Uses firstOrCreate pattern
     * - Creates Favorite if not exists (user_id, product_id)
     * - Does nothing if already favorited (idempotent)
     * 
     * Response:
     * - HTML: Back with "Added to favorites!" message
     * - JSON: {"success": true, "message": "Added to favorites!"}
     * 
     * Auto-response format detection:
     * - AJAX calls → JSON response
     * - Form submissions → HTML redirect
     * 
     * Use case:
     * - Product page "Save for later" button
     * - One-way operation (doesn't toggle)
     * - Use toggle() for star icons
     */
    public function add(products $product)
    {
        Favorite::firstOrCreate([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
        ]);

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Added to favorites!']);
        }

        return back()->with('success', 'Added to favorites!');
    }

    /**
     * Remove product from favorites
     * 
     * @param products $product - Product to unfavorite (route model binding)
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     * 
     * Operation:
     * - Queries Favorite by user_id + product_id
     * - Deletes all matching records (should be 0 or 1)
     * - No-op if not in favorites (safe to call multiple times)
     * 
     * Response:
     * - HTML: Back with "Removed from favorites." message
     * - JSON: {"success": true, "message": "Removed from favorites."}
     * 
     * Auto-response format detection:
     * - AJAX calls → JSON response
     * - Form submissions → HTML redirect
     * 
     * Use case:
     * - Favorites list "Remove" button
     * - One-way operation (doesn't toggle)
     * - Use toggle() for star icons
     */
    public function remove(products $product)
    {
        Favorite::where('user_id', Auth::id())->where('product_id', $product->id)->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Removed from favorites.']);
        }

        return back()->with('success', 'Removed from favorites.');
    }

    /**
     * Toggle favorite status (add if not favorite, remove if already)
     * 
     * @param products $product - Product to toggle (route model binding)
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse - With added/removed message
     * 
     * Logic:
     * 1. Check if product is favorited by user
     * 2. If yes → Delete, set added=false
     * 3. If no → Create, set added=true
     * 
     * Response messages:
     * - Add case: "Added to favorites!"
     * - Remove case: "Removed from favorites!"
     * 
     * JSON Response:
     * - {"success": true, "added": true/false, "message": "..."}
     * - added flag allows frontend to update UI (star fill/outline)
     * 
     * HTML Response:
     * - Back with appropriate success message
     * 
     * Use case:
     * - Star/heart icons on product cards
     * - Users click to toggle favorite state
     * - Frontend can use 'added' flag to update icon appearance
     * 
     * Advantages over separate add/remove:
     * - Single endpoint for both operations
     * - Clients know toggle result (useful for UI updates)
     * - Reduces HTTP requests
     */
    public function toggle(products $product)
    {
        $fav = Favorite::where('user_id', Auth::id())->where('product_id', $product->id)->first();

        if ($fav) {
            $fav->delete();
            $message = 'Removed from favorites!';
            $added = false;
        } else {
            Favorite::create(['user_id' => Auth::id(), 'product_id' => $product->id]);
            $message = 'Added to favorites!';
            $added = true;
        }

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'added' => $added, 'message' => $message]);
        }

        return back()->with('success', $message);
    }
}
