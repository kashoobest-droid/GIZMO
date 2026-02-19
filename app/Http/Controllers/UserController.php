<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

/**
 * UserController - Admin user management
 * 
 * Handles CRUD operations for user accounts (admin only)
 * 
 * Methods:
 * - index(): List all users
 * - edit(): Show edit form
 * - update(): Save user changes
 * - destroy(): Delete user account
 * 
 * Security:
 * - All methods except index are protected by UserPolicy
 * - Only admins (is_admin=1) can edit/delete users
 * - Prevents IDOR attacks via authorization checks
 * 
 * Fields managed:
 * - name: Display name
 * - email: Unique email address
 * - phone: Contact number
 * - country, street_name, building_name, floor_apartment, landmark, city_area
 * - is_admin: Administrator role (toggle-able by other admins)
 * 
 * Policy enforcement:
 * - UserPolicy.update() checks is_admin
 * - UserPolicy.delete() checks is_admin
 * 
 * Access control:
 * - Implemented via Laravel policies in UserPolicy
 * - Registered in AuthServiceProvider
 * - Throws 403 Forbidden if authorization fails
 */
class UserController extends Controller
{
    /**
     * List all users (Admin only, protected by middleware/policy)
     * 
     * @return \Illuminate\View\View - users.index with all users
     * 
     * Authorization:
     * - Admin only via middleware
     * - No policy check (listing allowed for admins)
     * 
     * Data:
     * - User::all() - All users with full details
     * 
     * View features:
     * - Table of users
     * - Name, email, phone, country columns
     * - Edit button (protected by policy)
     * - Delete button (protected by policy)
     * - Admin badge indicator
     * 
     * Performance:
     * - No pagination (may need pagination for many users)
     * - All users loaded into memory
     */
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    /**
     * Show user edit form (Admin only, protected by UserPolicy)
     * 
     * @param User $user - User to edit (route model binding)
     * @return \Illuminate\View\View - users.edit form view
     * 
     * Authorization:
     * - $this->authorize('update', $user) enforced
     * - Only admins can edit users
     * - User can edit their own profile via ProfileController
     * - Prevents IDOR by checking is_admin flag
     * 
     * Route model binding:
     * - Automatically fetches user by route parameter
     * - Returns 404 if user not found
     * 
     * Form fields:
     * - name, email (both required)
     * - phone, country, street_name, building_name, floor_apartment, landmark, city_area (optional)
     * - is_admin checkbox (for granting/revoking admin role)
     * 
     * View features:
     * - Pre-filled with current user data
     * - Email unique validation (excluding current user)
     * - Admin checkbox visible to current admin
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    /**
     * Update user account details (Admin only, protected by UserPolicy + authorization)
     * 
     * @param Request $request - Contains user fields to update
     * @param User $user - User being updated (route model binding)
     * @return \Illuminate\Http\RedirectResponse - Redirects to users.index with message
     * 
     * Authorization:
     * - $this->authorize('update', $user) enforced first
     * - Only admins can reach this method
     * - UserPolicy checks is_admin flag
     * - Returns 403 Forbidden if unauthorized
     * 
     * Validation:
     * - name: Required, string, max 255
     * - email: Required, unique (excluding current user), max 255
     * - phone: Optional, string, max 30
     * - country, street_name, building_name, floor_apartment, landmark, city_area: Optional, max 255
     * - is_admin: Optional boolean (checked via request->has())
     * 
     * is_admin handling:
     * - Checkbox returns 'on' or missing (not sent if unchecked)
     * - Code converts to 1 if checked, 0 if unchecked
     * - Ensures boolean 0/1 stored in database
     * 
     * Email uniqueness:
     * - unique:users,email,{user_id} allows user to keep same email
     * - Prevents duplicate emails from other users
     * 
     * Response:
     * - Redirect to users.index with "User updated successfully." message
     * 
     * Use case:
     * - Admin updating user info
     * - Granting/revoking admin role
     * - Updating address details
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:30',
            'country' => 'nullable|string|max:255',
            'street_name' => 'nullable|string|max:255',
            'building_name' => 'nullable|string|max:255',
            'floor_apartment' => 'nullable|string|max:255',
            'landmark' => 'nullable|string|max:255',
            'city_area' => 'nullable|string|max:255',
            'is_admin' => 'nullable|boolean',
            'admin_scopes' => 'nullable|array',
            'admin_scopes.*' => 'string|in:products,categories,offers,users,coupons,orders',
            'is_master_admin' => 'nullable|boolean',
        ]);

        // Ensure is_admin is stored as 0/1
        $validated['is_admin'] = $request->has('is_admin') ? 1 : 0;

        // Handle admin_scopes and master flag only when current user is master admin
        $current = auth()->user();

        if ($current && method_exists($current, 'isMasterAdmin') && $current->isMasterAdmin()) {
            // admin_scopes may be null -> store as empty array
            $validated['admin_scopes'] = $request->input('admin_scopes', []);
            $validated['is_master_admin'] = $request->has('is_master_admin') ? 1 : 0;
        } else {
            // Prevent non-master admins from changing scopes or master flag
            unset($validated['admin_scopes'], $validated['is_master_admin']);
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Delete user account (Admin only, protected by UserPolicy)
     * 
     * @param User $user - User to delete (route model binding)
     * @return \Illuminate\Http\RedirectResponse - Redirects to users.index with message
     * 
     * Authorization:
     * - $this->authorize('delete', $user) enforced first
     * - Only admins can delete users
     * - UserPolicy checks is_admin flag
     * - Returns 403 Forbidden if unauthorized
     * 
     * Cascading deletes:
     * - CartItem records (user's shopping cart)
     * - Favorite records (user's wishlists)
     * - Order records (user's order history)
     * - Review records (user's reviews)
     * - All relationships cleaned up automatically
     * 
     * Response:
     * - Redirect to users.index with "User deleted successfully." message
     * 
     * Warning:
     * - Permanent deletion (no soft delete/restore)
     * - All user data removed from system
     * - Orders and history kept (would need separate cascade config)
     * 
     * Audit trail:
     * - No logging (TODO: Add audit log before deletion)
     * 
     * Use case:
     * - Admin removing inactive users
     * - Account deletion request fulfillment
     * - User cleanup
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
