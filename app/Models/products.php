<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\category;
use App\Models\Offer;
use App\Models\ProductImage;

class products extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
        'total',
        'Category_id'
    ];

    public function category()
    {
        return $this->belongsTo(category::class, 'Category_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    public function offer()
    {
        return $this->hasOne(Offer::class, 'product_id');
    }

    /** Whether the product's offer should be shown on the storefront (date range and timezone aware). */
    public function hasVisibleOffer(): bool
    {
        if (!$this->relationLoaded('offer') || !$this->offer) {
            return false;
        }
        $offer = $this->offer;
        $now = now();
        // Start: hide only if we're before the start-of-day of starts_at
        if ($offer->starts_at !== null) {
            $start = \Carbon\Carbon::parse($offer->starts_at)->startOfDay();
            if ($now->lessThan($start)) {
                return false;
            }
        }
        // End: hide only if we're after the end-of-day of ends_at (so "Feb 20" = valid all day Feb 20)
        if ($offer->ends_at !== null) {
            $end = \Carbon\Carbon::parse($offer->ends_at)->endOfDay();
            if ($now->greaterThan($end)) {
                return false;
            }
        }
        return true;
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'product_id');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'product_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id');
    }

    public function stockNotifications()
    {
        return $this->hasMany(StockNotification::class, 'product_id');
    }

    public function averageRating(): float
    {
        return (float) $this->reviews()->avg('rating');
    }

    /**
     * Check if a specific user has purchased this product
     * @param User $user
     * @return bool
     */
    public function hasPurchasedBy(User $user): bool
    {
        return OrderItem::whereHas('order', function($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->where('status', '!=', 'cancelled');
        })->where('product_id', $this->id)->exists();
    }
}
