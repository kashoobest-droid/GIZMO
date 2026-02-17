<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\products;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = Order::count();
        $totalRevenue = Order::whereIn('status', ['pending', 'processing', 'shipped', 'delivered'])->sum('total');
        $productsCount = products::count();
        $usersCount = User::where('is_admin', false)->count();
        $lowStockProducts = products::where('quantity', '<', 5)->where('quantity', '>=', 0)->get();
        $outOfStockCount = products::where('quantity', '<', 1)->count();
        $recentOrders = Order::with('user')->latest()->take(10)->get();

        return view('admin.dashboard', compact(
            'totalOrders',
            'totalRevenue',
            'productsCount',
            'usersCount',
            'lowStockProducts',
            'outOfStockCount',
            'recentOrders'
        ));
    }
}
