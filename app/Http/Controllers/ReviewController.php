<?php

namespace App\Http\Controllers;

use App\Models\products;
use App\Models\Review;
use App\Models\ReviewReaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * ReviewController - Manage product reviews and feedback
 * 
 * Handles customer review lifecycle: create, update, delete, react
 * 
 * Methods:
 * - store(): Create/update review for purchased product
 * - update(): Edit existing review (owner only)
 * - destroy(): Delete review (owner only)
 * - react(): Mark review as helpful/not helpful
 * 
 * Security:
 * - Verified purchase requirement: Only buyers can review
 * - Ownership validation: Users can only edit/delete their own reviews
 * - Reaction anti-spam: Users can only react once per review
 * 
 * Business logic:
 * - Reviews are tied to product + user (one review per user per product)
 * - UpdateOrCreate pattern prevents duplicate reviews
 * - Reactions are separate from reviews (users mark reviews as helpful)
 * - Reactions can be toggled (click again to remove)
 * 
 * Review data:
 * - rating: 1-5 stars
 * - comment: Max 1000 characters
 * - user_id: Who wrote it
 * - product_id: Which product
 * 
 * Reaction types:
 * - helpful: Mark as helpful
 * - not_helpful: Mark as unhelpful
 * - Toggle-able: Clicking same reaction removes it
 */
class ReviewController extends Controller
{
    /**
     * Create or update review for a product
     * 
     * @param Request $request - Contains: rating (1-5), comment (optional, max 1000)
     * @param products $product - Product being reviewed
     * @return \Illuminate\Http\RedirectResponse - Back to product page
     * 
     * Verification:
     * - Checks that user has purchased this product (verified buyer)
     * - Returns error if not a verified buyer
     * - Protects against fake reviews from non-buyers
     * 
     * Database operation:
     * - Uses updateOrCreate pattern
     * - If user already reviewed this product → update existing
     * - If new → create new review
     * - Prevents duplicate reviews from same user for same product
     * 
     * Validation:
     * - rating: Required, integer, 1-5 only
     * - comment: Optional, string, max 1000 chars
     * 
     * Response:
     * - Success message: "Thank you for your review!"
     * - Redirects back to product page
     * - Updated rating/comment immediately visible
     */
    public function store(Request $request, products $product)
    {
        // Verify that the user has actually purchased this product
        if (!$product->hasPurchasedBy(Auth::user())) {
            return back()->with('error', 'Only verified buyers can leave reviews. Please purchase this product first.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::updateOrCreate(
            [
                'product_id' => $product->id,
                'user_id' => Auth::id(),
            ],
            [
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]
        );

        return back()->with('review_success', 'Thank you for your review!');
    }

    /**
     * Update existing review (owner only)
     * 
     * @param Request $request - Contains: rating (1-5), comment (optional, max 1000)
     * @param Review $review - Review to update
     * @return \Illuminate\Http\RedirectResponse - Back to product page
     * 
     * Authorization:
     * - Checks review.user_id === Auth::id()
     * - Returns error if user is not the reviewer
     * - Prevents users from editing others' reviews
     * 
     * Validation:
     * - rating: Required, integer, 1-5
     * - comment: Optional, string, max 1000 chars
     * 
     * Update fields:
     * - rating: 1-5 star value
     * - comment: Review text
     * - updated_at: Auto-updated by Laravel
     * 
     * Response:
     * - Success: "Your review has been updated!"
     * - Redirects back to prevent accidental resubmission
     * - Changes visible immediately on product page
     */
    public function update(Request $request, Review $review)
    {
        // Verify ownership
        if ($review->user_id !== Auth::id()) {
            return back()->with('error', 'You can only edit your own reviews.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('review_success', 'Your review has been updated!');
    }

    /**
     * Delete review (owner only)
     * 
     * @param Review $review - Review to delete
     * @return \Illuminate\Http\RedirectResponse - Back to product page
     * 
     * Authorization:
     * - Checks review.user_id === Auth::id()
     * - Returns error if not the original reviewer
     * - Prevents users from deleting others' reviews
     * 
     * Cascade behavior:
     * - Deletes all ReviewReaction records linked to this review
     * - Removes all helpful/not-helpful votes for this review
     * - All data cleaned up (no orphaned records)
     * 
     * Response:
     * - Success: "Your review has been deleted!"
     * - Redirects back to product
     * - Review immediately gone from product page
     * 
     * Note:
     * - No soft delete (permanent removal)
     * - No archive/restore option
     */
    public function destroy(Review $review)
    {
        // Verify ownership
        if ($review->user_id !== Auth::id()) {
            return back()->with('error', 'You can only delete your own reviews.');
        }

        $review->delete();

        return back()->with('review_success', 'Your review has been deleted!');
    }

    /**
     * Mark review as helpful/not-helpful or toggle reaction
     * 
     * @param Request $request - Contains: reaction_type (helpful|not_helpful)
     * @param Review $review - Review being reacted to
     * @return \Illuminate\Http\RedirectResponse - Back to product page
     * 
     * Reaction types:
     * - helpful: Mark review as useful to other shoppers
     * - not_helpful: Mark review as not useful
     * 
     * Business logic:
     * - One reaction per user per review (prevents vote spam)
     * - Finds existing reaction for this user + review
     * 
     * Three outcomes:
     * 1. No existing reaction → Create new one
     *    - Saves new ReviewReaction record
     *    - Message: "Thank you for your feedback!"
     * 
     * 2. Existing reaction (same type) → Remove/toggle it
     *    - Deletes the ReviewReaction record
     *    - Allows users to un-vote
     *    - Message: "Reaction removed!"
     * 
     * 3. Existing reaction (different type) → Update it
     *    - Changes reaction_type from helpful ↔ not_helpful
     *    - User switches their vote
     *    - Message: "Reaction updated!"
     * 
     * Performance:
     * - Direct database lookup (not N+1 issue)
     * - Single query to find existing reaction
     * 
     * Note:
     * - Reactions not tied to verified purchase
     * - Any logged-in user can react to any review
     * - Useful for ranking reviews by helpfulness
     */
    public function react(Request $request, Review $review)
    {
        $request->validate([
            'reaction_type' => 'required|in:helpful,not_helpful',
        ]);

        $reaction_type = $request->reaction_type;

        // Check if user already reacted
        $existingReaction = ReviewReaction::where('review_id', $review->id)
                                        ->where('user_id', Auth::id())
                                        ->first();

        if ($existingReaction) {
            // If clicking the same reaction, remove it
            if ($existingReaction->reaction_type === $reaction_type) {
                $existingReaction->delete();
                return back()->with('review_success', 'Reaction removed!');
            } else {
                // Otherwise, update the reaction
                $existingReaction->update(['reaction_type' => $reaction_type]);
                return back()->with('review_success', 'Reaction updated!');
            }
        } else {
            // Create new reaction
            ReviewReaction::create([
                'review_id' => $review->id,
                'user_id' => Auth::id(),
                'reaction_type' => $reaction_type,
            ]);
            return back()->with('review_success', 'Thank you for your feedback!');
        }
    }
}
