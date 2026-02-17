<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'type', 'value', 'min_purchase',
        'starts_at', 'ends_at', 'use_limit', 'used_count', 'active',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_purchase' => 'decimal:2',
        'starts_at' => 'date',
        'ends_at' => 'date',
    ];

    public function isValid(float $subtotal): bool
    {
        if (!$this->active) {
            return false;
        }
        if ($this->starts_at && now()->lessThan($this->starts_at)) {
            return false;
        }
        if ($this->ends_at && now()->greaterThan($this->ends_at)) {
            return false;
        }
        if ($this->use_limit !== null && $this->used_count >= $this->use_limit) {
            return false;
        }
        if ($this->min_purchase !== null && $subtotal < (float) $this->min_purchase) {
            return false;
        }
        return true;
    }

    public function discountFor(float $subtotal): float
    {
        if ($this->type === 'percent') {
            return round($subtotal * ((float) $this->value / 100), 2);
        }
        return min((float) $this->value, $subtotal);
    }
}
