<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\products;
use App\Models\Order;
use App\Models\OrderItem;

class OrderStockRestoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_rejecting_bankak_payment_restores_stock()
    {
        // Create a product with 11 in stock
        $category = \App\Models\category::create(['name' => 'Default', 'description' => 'Default']);

        $product = products::create([
            'name' => 'Test Product',
            'slug' => 'test-product',
            'description' => 'Test',
            'price' => 100.00,
            'quantity' => 11,
            'total' => 1100.00,
            'Category_id' => $category->id,
        ]);

        // Create a normal user and an admin
        $user = User::create([
            'name' => 'Customer',
            'email' => 'customer@example.com',
            'password' => bcrypt('secret'),
        ]);

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('secret'),
            'is_admin' => true,
            'admin_scopes' => ['orders'],
        ]);

        // Create an order and an order item (simulate order created and stock decremented)
        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'pending',
            'payment_method' => 'bankak',
            'payment_status' => 'awaiting_admin_approval',
            'total' => 100.00,
        ]);

        $item = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'price' => $product->price,
            'quantity' => 1,
            'subtotal' => $product->price,
        ]);

        // Decrement product to simulate the checkout path
        $product->decrement('quantity', 1);
        $this->assertEquals(10, $product->fresh()->quantity);

        // Admin rejects the payment (route: admin.orders.rejectPayment)
        // Call controller method directly to avoid routing/middleware nuances in tests
        $controller = new \App\Http\Controllers\OrderController();
        $resp = $controller->rejectPayment(new \Illuminate\Http\Request(), $order);
        // Controller redirects back on success — ensure order updated
        // Product quantity should be restored to 11
        $this->assertEquals(11, $product->fresh()->quantity);

        // Order should now be cancelled and stock_restored true
        $this->assertEquals('cancelled', $order->fresh()->status);
        $this->assertTrue((bool) $order->fresh()->stock_restored);
    }
}
