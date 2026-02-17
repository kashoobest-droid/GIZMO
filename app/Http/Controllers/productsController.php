<?php

namespace App\Http\Controllers;

use App\Models\products;
use App\Helpers\CloudinaryHelper;
use Illuminate\Http\Request;
use App\Models\category;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * ProductsController - Manage product catalog
 * 
 * Handles both admin operations (CRUD) and customer storefront display.
 * 
 * Admin methods:
 * - index(): List all products (database)
 * - create(): Show create form
 * - store(): Save new product with images (max 6)
 * - edit(): Show edit form
 * - update(): Update product and manage images
 * - destroy(): Delete product
 * 
 * Customer methods:
 * - show(): Display single product with reviews and related items
 * - storefront(): Public catalog with search, filtering, sorting, pagination
 * 
 * Image handling:
 * - Accepts JPEG, PNG, JPG, GIF, SVG
 * - Max 6 images per product
 * - Rollback if no images provided on create
 * - Automatic file deletion when images removed
 * 
 * Stock notifications:
 * - Automatic email when out-of-stock product gets restocked
 * - Triggered via NotifyStockBackJob queue
 * 
 * Authorization:
 * - Admin only for index, create, store, edit, update, destroy
 * - Public access for show(), storefront()
 * 
 * Database model: products (note: lowercase naming)
 */
class productsController extends Controller
{
    /**
     * Display a listing of all products (Admin only)
     * 
     * @return \Illuminate\View\View - product_index view with all products
     * 
     * Features:
     * - Eager loads relationships (images, category, offer)
     * - Used by admins to see entire catalog
     * - No pagination (may need pagination for large catalogs)
     * 
     * Performance note:
     * - Consider adding pagination if > 1000 products
     */
    public function index()
    {
        $products = products::with('images', 'category', 'offer')->get();
        return view("product_index", compact('products'));
    }

    /**
     * Show product creation form (Admin only)
     * 
     * @return \Illuminate\View\View - product_add form view
     * 
     * Form fields filled by: categories dropdown
     */
    public function create()
    {
        $categories = category::all();
        return view('product_add', compact('categories'));
    }

    /**
     * Store newly created product with images
     * 
     * @param Request $request - Expects: name, description, price, quantity, category_id, images[]
     * @returns \Illuminate\Http\RedirectResponse - Redirects to product.index with success message
     * 
     * Validation:
     * - name: Required, string, max 255 chars
     * - description: Optional, string
     * - price: Required, numeric, >= 0
     * - quantity: Required, integer, >= 0
     * - category_id: Required, exists in categories table
     * - images[]: Required (at least 1), image type, JPEG/PNG/JPG/GIF/SVG only
     * 
     * Image processing:
     * - Accepts up to 6 images per product
     * - Stores in public/upload directory with uniqid prefix
     * - Creates ProductImage records linking to product
     * - Validates MIME types and prevents invalid uploads
     * 
     * Business logic:
     * - Calculates 'total' field: price × quantity
     * - Maps form's 'category_id' to database column 'Category_id'
     * - Rollback & error if < 1 valid image saved
     * 
     * Rollback behavior:
     * - If no valid images, product is deleted and error returned
     * - Prevents orphaned products without images
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $data = $request->all();
        $data['total'] = $request->input('price') * ($request->input('quantity') ?? 1);
        if (isset($data['category_id'])) {
            $data['Category_id'] = $data['category_id'];
            unset($data['category_id']);
        }
        $product = products::create($data);

        // Handle image uploads (files)
        $imageCount = 0;
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                if ($file && $file->isValid() && $imageCount < 6) {
                    $filename = uniqid('prodimg_').'.'.$file->getClientOriginalExtension();
                    $path = Storage::disk('cloudinary')->putFileAs('products', $file, $filename);
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => CloudinaryHelper::getUrl($path),
                    ]);
                    $imageCount++;
                }
            }
        }

        if ($imageCount < 1) {
            // Rollback product if no images provided
            $product->delete();
            return back()->withErrors(['images' => 'At least one valid image is required.'])->withInput();
        }

        return redirect()->route('product.index')->with('success', 'Product added successfully!');
    }

    /**
     * Display single product with reviews, related products, and purchase options
     * 
     * @param products $product - The product to display (route model binding)
     * @return \Illuminate\View\View - product_show view with product details
     * 
     * Data loaded (eager loading):
     * - images: Product images (up to 6)
     * - category: Product's category
     * - offer: Active offer if exists
     * - reviews.user: Customer reviews with reviewer details
     * 
     * Related products:
     * - Shows up to 4 products from same category
     * - Excludes the current product from results
     * - Sorted by ID (newest first)
     * 
     * View features:
     * - Breadcrumb navigation (Category > Product)
     * - Product details (name, price, description, rating)
     * - Image gallery with zoom
     * - Stock status (in stock / out of stock)
     * - Customer reviews and ratings
     * - Social sharing buttons
     * - Related products carousel
     * - Add to cart / Add to favorites buttons
     * 
     * Security:
     * - Uses Route Model Binding (automatic)
     * - Anyone can view (public facing)
     */
    public function show(products $product)
    {
        $product->load('images', 'category', 'offer', 'reviews.user');
        $relatedProducts = products::where('Category_id', $product->Category_id)
            ->where('id', '!=', $product->id)
            ->with('images', 'category', 'offer')
            ->limit(4)
            ->get();
        return view('product_show', compact('product', 'relatedProducts'));
    }

    /**
     * Show product edit form (Admin only)
     * 
     * @param products $product - Product to edit (route model binding)
     * @return \Illuminate\View\View - product_update form view
     * 
     * Form will be pre-filled with:
     * - All product fields (name, description, price, quantity)
     * - Current category selection
     * - List of current images with remove options
     * - Category dropdown
     * 
     * Route Model Binding:
     * - Automatically fetches product by ID from route parameter
     * - Returns 404 if product not found
     */
    public function edit(products $product)
    {
        // Laravel's Route Model Binding automatically fetches the product by ID
        $categories = category::all();
        return view('product_update', compact('product', 'categories'));
    }

    /**
     * Update existing product with new images and data
     * 
     * @param Request $request - Contains: name, description, price, quantity, category_id, images[], remove_images[]
     * @param products $product - Product to update (route model binding)
     * @return \Illuminate\Http\RedirectResponse - Redirects to product.index with message
     * 
     * Validation (same as store):
     * - name: Required, 3-255 chars
     * - description: Optional
     * - price: Required, numeric, >= 0
     * - quantity: Required, integer, >= 0
     * - category_id: Required, exists
     * - images[]: Optional, but validated if provided
     * - remove_images[]: Optional array of image IDs to delete
     * 
     * Image removal process:
     * - Takes array of image IDs from form
     * - Physically deletes files from public/upload if local
     * - Removes ProductImage database records
     * 
     * Image addition process:
     * - Accepts new images up to maxmimum 6 total
     * - Counts existing images after removal
     * - Stops processing if reaches 6 limit
     * 
     * Stock notifications:
     * - Checks if product was out-of-stock (quantity < 1)
     * - After update, if now in stock → dispatch NotifyStockBackJob
     * - Job sends emails to customers with stock notifications
     * 
     * Constraints:
     * - Minimum 1 image required (error if < 1 after update)
     * - Maximum 6 images per product
     * - Local files only (no URL images)
     */
    public function update(Request $request, products $product)
    {
        $validatedData = $request->validate([
            'name' => 'required|min:3|max:255|string',
            'description' => 'nullable|string',
            'price'=> 'required|numeric|min:0',
            'quantity'=> 'required|integer|min:0',
            'category_id'=> 'required|exists:categories,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'remove_images' => 'nullable|array',
        ]);

        // Map form's category_id to DB column name used in migration/model
        if (isset($validatedData['category_id'])) {
            $validatedData['Category_id'] = $validatedData['category_id'];
            unset($validatedData['category_id']);
        }
        $wasOutOfStock = $product->quantity < 1;
        $product->update($validatedData);

        // Remove selected images
        if ($request->filled('remove_images')) {
            $removeIds = $request->input('remove_images');
            foreach ($product->images()->whereIn('id', $removeIds)->get() as $img) {
                // Cloudinary handles file deletion automatically
                $img->delete();
            }
        }

        // Count current images after removal
        $currentImageCount = $product->images()->count();

        // Add new images (files)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                if ($file && $file->isValid() && $currentImageCount < 6) {
                    $filename = uniqid('prodimg_').'.'.$file->getClientOriginalExtension();
                    $path = Storage::disk('cloudinary')->putFileAs('products', $file, $filename);
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => CloudinaryHelper::getUrl($path),
                    ]);
                    $currentImageCount++;
                }
            }
        }

        // Ensure at least 1 image remains
        if ($product->images()->count() < 1) {
            return back()->withErrors(['images' => 'At least one image is required.']);
        }

        if ($wasOutOfStock && $product->fresh()->quantity > 0) {
            \App\Jobs\NotifyStockBackJob::dispatch($product->fresh());
        }

        return redirect()->route('product.index')->with('success', 'Product updated successfully!');
    }

    /**
     * Delete product from storage (Admin only)
     * 
     * @param string $id - Product ID to delete
     * @return \Illuminate\Http\RedirectResponse - Redirects to product.index with message
     * 
     * Process:
     * - Find product by ID
     * - If not found → error message and redirect
     * - If found → delete product & all relationships (images, reviews, etc.)
     * 
     * Cascading deletes:
     * - ProductImage records (cascade configured in ProductImage model)
     * - CartItem records (users' carts)
     * - Favorite records (users' favorites)
     * - Review records (product reviews)
     * - StockNotification records
     * 
     * Files cleanup:
     * - Note: Physical files in public/upload NOT auto-deleted
     * - TODO: Add file cleanup logic to avoid orphaned images
     */
    public function destroy(string $id)
    {
        $product = products::find($id);
        if (!$product) {
            return redirect()->route('product.index')->with('error', 'Product not found.');
        }
        $product->delete();
        return redirect()->route('product.index')->with('success', 'Product deleted successfully!');
    }

    /**
     * Display storefront (public catalog) with search, filtering, and sorting
     * 
     * @param Request $request - Query parameters: q, category, sort
     * @return \Illuminate\View\View - gizmo_store storefront view with products
     * 
     * Query parameters:
     * - q: Search term (searches name, description, category name)
     * - category: Filter by category ID
     * - sort: Sort order (newest, price_asc, price_desc, name_asc, name_desc)
     * 
     * Search implementation:
     * - Searches product name and description with LIKE
     * - Also searches by category name via relationship
     * - Case-insensitive search
     * 
     * Category filtering:
     * - Optional parameter
     * - Filters by Category_id field
     * - Used for breadcrumb display
     * 
     * Sorting options:
     * - newest (default): OrderBy ID descending (newest first)
     * - price_asc: Lowest price first
     * - price_desc: Highest price first
     * - name_asc: A-Z alphabetical
     * - name_desc: Z-A reverse alphabetical
     * 
     * Data loading:
     * - Eager loads: images, category, offer
     * - Paginated: 12 products per page
     * - Preserves query string on pagination links
     * 
     * User-specific data:
     * - Cart items: Shows what's in user's cart
     * - Favorites: Shows which products are favorited
     * - Cart quantities: Pre-fills quantity selector
     * - Cart count: For navbar display
     * 
     * Performance:
     * - Queries optimized with eager loading
     * - Pagination prevents loading all products at once
     * - Array flipping for O(1) cart/favorite lookups
     */
    public function storefront(Request $request)
    {
        $q = $request->query('q');
        $categoryId = $request->query('category');
        $sort = $request->query('sort', 'newest');
        $categories = category::all();

        $qb = products::query();

        if ($q) {
            $qb->where(function($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%")
                    ->orWhereHas('category', function($q2) use ($q) {
                        $q2->where('name', 'like', "%{$q}%");
                    });
            });
        }

        if ($categoryId) {
            $qb->where('Category_id', $categoryId);
        }

        match ($sort) {
            'price_asc' => $qb->orderBy('price'),
            'price_desc' => $qb->orderByDesc('price'),
            'name_asc' => $qb->orderBy('name'),
            'name_desc' => $qb->orderByDesc('name'),
            default => $qb->orderByDesc('id'),
        };

        $products = $qb->with('images', 'category', 'offer')->paginate(12)->withQueryString();

        $cartProductIds = Auth::check() ? Auth::user()->cartItems()->pluck('product_id')->flip()->toArray() : [];
        $favoriteProductIds = Auth::check() ? Auth::user()->favorites()->pluck('product_id')->flip()->toArray() : [];
        $cartQuantities = Auth::check() ? Auth::user()->cartItems()->pluck('quantity', 'product_id')->toArray() : [];
        $cartCount = Auth::check() ? Auth::user()->cartItems()->sum('quantity') : 0;

        $categoryForBreadcrumb = $categoryId ? category::find($categoryId) : null;

        return view('gizmo_store', compact('products', 'categories', 'q', 'categoryId', 'categoryForBreadcrumb', 'sort', 'cartProductIds', 'favoriteProductIds', 'cartQuantities', 'cartCount'));
    }
}
