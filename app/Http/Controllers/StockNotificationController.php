<?php

namespace App\Http\Controllers;

use App\Models\products;
use App\Models\StockNotification;
use Illuminate\Http\Request;

class StockNotificationController extends Controller
{
    public function store(Request $request, products $product)
    {
        $request->validate(['email' => 'required|email']);

        if ($product->quantity > 0) {
            return back()->with('info', 'This product is already in stock. You can add it to your cart.');
        }

        StockNotification::firstOrCreate(
            ['product_id' => $product->id, 'email' => $request->email],
            ['notified' => false]
        );

        return back()->with('success', 'We\'ll email you when this product is back in stock.');
    }
}
