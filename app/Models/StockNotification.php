<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockNotification extends Model
{
    protected $fillable = ['product_id', 'email', 'notified'];

    protected $casts = ['notified' => 'boolean'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(products::class, 'product_id');
    }
}
