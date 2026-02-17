<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderTrackingController extends Controller
{
    public function show()
    {
        return view('order-tracking');
    }

    public function track(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer',
            'email' => 'required|email',
        ]);

        $order = Order::with('items.product')
            ->where('id', $request->order_id)
            ->whereHas('user', fn ($q) => $q->where('email', $request->email))
            ->first();

        if (!$order) {
            return back()->with('error', 'No order found with that ID and email. Please check and try again.');
        }

        return view('order-tracking-result', compact('order'));
    }
}
