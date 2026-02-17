<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Review extends Model
{
    protected $fillable = ['product_id', 'user_id', 'rating', 'comment'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(products::class, 'product_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(ReviewReaction::class);
    }

    public function helpfulCount(): int
    {
        return $this->reactions()->where('reaction_type', 'helpful')->count();
    }

    public function notHelpfulCount(): int
    {
        return $this->reactions()->where('reaction_type', 'not_helpful')->count();
    }

    public function userReaction(User $user): ?ReviewReaction
    {
        return $this->reactions()->where('user_id', $user->id)->first();
    }
}
