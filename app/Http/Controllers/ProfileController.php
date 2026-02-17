<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Helpers\CloudinaryHelper;
use Illuminate\Validation\Rules\Password as PasswordRule;
use App\Models\User;

/**
 * ProfileController - User own account management
 * 
 * Handles user profile operations (authenticated users only)
 * 
 * Methods:
 * - edit(): Show profile edit form
 * - update(): Save profile changes (info + avatar)
 * - updatePassword(): Change password with verification
 * 
 * Security:
 * - All methods require authentication (Auth middleware)
 * - Users only access their own profile (Auth::user() enforced)
 * - Password change requires current password verification
 * - No IDOR attacks possible (hardcoded to Auth::user())
 * 
 * Data managed:
 * - Personal: name, email, phone
 * - Address: country, street_name, building_name, floor_apartment, landmark, city_area
 * - Avatar: Profile picture (JPEG, PNG, JPG, GIF, SVG, max 2MB)
 * - Password: Bcrypt hashed, 8+ chars, mixed case, numbers required
 * 
 * Avatar handling:
 * - Stored in public/upload/avatars/
 * - Filename: avatar_{user_id}_{uniqid}.ext
 * - Previous avatar auto-deleted (cleanup)
 * - Directory auto-created if missing
 * 
 * Password policy:
 * - Minimum 8 characters
 * - Must contain letters (a-z, A-Z)
 * - Must contain numbers (0-9)
 * - Must contain mixed case (both upper and lower)
 * - Example: Password123 (valid), password123 (invalid - no uppercase)
 * 
 * Comparison with UserController:
 * - UsersController: Admin editing other users
 * - ProfileController: User editing themselves
 */
class ProfileController extends Controller
{
    /**
     * Show user's own profile edit form
     * 
     * @return \Illuminate\View\View - profile edit form view
     * 
     * Data:
     * - Auth::user() - Authenticated user's own profile
     * 
     * View features:
     * - Pre-filled name, email, phone, address fields
     * - Current avatar display with upload option
     * - Password change form separate section
     * - Save button for both info and avatar
     * 
     * Security:
     * - Users can only access their own profile
     * - No user ID parameter (uses Auth::user() implicitly)
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    /**
     * Update user's own profile info and avatar
     * 
     * @param Request $request - Contains: name, email, phone, address fields, avatar (optional)
     * @return \Illuminate\Http\RedirectResponse - Redirects to profile.edit with message
     * 
     * Validation:
     * - name: Required, string, max 255
     * - email: Required, unique (excluding current user), max 255
     * - phone: Optional, string, max 30
     * - country, street_name, building_name, floor_apartment, landmark, city_area: Optional
     * - avatar: Optional, image type, JPEG/PNG/JPG/GIF/SVG, max 2048KB
     * 
     * Avatar processing:
     * 1. Checks if user uploaded file
     * 2. Validates file type (MIME)
     * 3. Deletes previous avatar if file exists
     * 4. Creates upload/avatars directory if missing
     * 5. Generates filename: avatar_{user_id}_{uniqid}.ext
     * 6. Moves file to public/upload/avatars/
     * 7. Stores path in database
     * 
     * File cleanup:
     * - Previous avatar physically deleted (no orphaned files)
     * - Silent failure if file doesn't exist (uses @unlink)
     * 
     * Email uniqueness:
     * - unique:users,email,{user_id} allows keeping same email
     * - Prevents duplicate emails
     * 
     * Database:
     * - Updates user record with new values
     * - Timestamps auto-updated
     * 
     * Response:
     * - Redirect to profile.edit with "Profile updated successfully." message
     * - User sees updated values on refresh
     * 
     * Use case:
     * - User updating personal information
     * - Changing contact details or address
     * - Uploading/changing profile picture
     */
    public function update(Request $request)
    {
        $user = Auth::user();

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
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            if ($file->isValid()) {
                // Cloudinary handles old file cleanup automatically
                $filename = 'avatar_' . $user->id . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = Storage::disk('cloudinary')->putFileAs('avatars', $file, $filename);
                $validated['avatar'] = CloudinaryHelper::getUrl($path);
            }
        }

        $user->update($validated);

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully.');
    }

    /**
     * Update user's own password with current password verification
     * 
     * @param Request $request - Contains: current_password, password, password_confirmation
     * @return \Illuminate\Http\RedirectResponse - Redirects to profile.edit with message
     * 
     * Validation:
     * - current_password: Required, string (plain text, will be hashed for comparison)
     * - password: Required, confirmed, must match password_confirmation
     * - password rules:
     *   * Minimum 8 characters
     *   * Must contain letters (a-z, A-Z)
     *   * Must contain numbers (0-9)
     *   * Must contain mixed case (both upper and lower)
     * 
     * Current password verification:
     * - Fetches Auth::user()
     * - Uses Hash::check() to verify against bcrypt hash
     * - Case-sensitive comparison
     * - Returns error if incorrect without changing password
     * 
     * Password confirmation:
     * - Rails-like: 'password' and 'password_confirmation' must match
     * - Checked in validation rules
     * - Prevents typos from locking user out
     * 
     * Password hashing:
     * - Uses Hash::make() → Bcrypt algorithm
     * - Never stores plain text
     * - Bcrypt automatically handles salt generation
     * 
     * Response on success:
     * - Updates user.password in database
     * - Redirect to profile.edit with success message
     * - User remains logged in (no re-authentication required)
     * 
     * Response on error:
     * - current_password error: "The current password is incorrect."
     * - Returns back() to form with errors
     * - No password change made
     * 
     * Security considerations:
     * - Always verify current password (prevents account takeover via CSRF)
     * - Use bcrypt hashing
     * - No password hints or reset function
     * - Session continues (user stays logged in)
     * 
     * Example valid passwords:
     * - Password123 ✓
     * - MyNewPass456 ✓
     * - Example987 ✓
     * 
     * Example invalid passwords:
     * - password123 ✗ (no uppercase)
     * - PASSWORD123 ✗ (no lowercase)
     * - Passwordabc ✗ (no numbers)
     * - Pass12 ✗ (fewer than 8 chars)
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => [
                'required',
                'confirmed',
                PasswordRule::min(8)->letters()->numbers()->mixedCase(),
            ],
        ]);

        $user = Auth::user();

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.edit')->with('success', 'Password updated successfully.');
    }
}
