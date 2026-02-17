<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\category;
use App\Models\products;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@kstech.com',
            'password' => Hash::make('password123'),
            'is_admin' => true,
        ]);

        // Create regular user
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
            'is_admin' => false,
        ]);

        // Create categories
        $categories = [
            ['name' => 'Laptops', 'description' => 'High-performance laptops and notebooks'],
            ['name' => 'Phones', 'description' => 'Smartphones and mobile devices'],
            ['name' => 'Tablets', 'description' => 'Tablets and iPad devices'],
            ['name' => 'Accessories', 'description' => 'Chargers, cables, cases and more'],
            ['name' => 'Audio', 'description' => 'Headphones, speakers and audio equipment'],
        ];

        foreach ($categories as $cat) {
            category::create($cat);
        }

        // Create sample products
        $productsData = [
            [
                'name' => 'MacBook Pro 16"',
                'description' => 'Powerful laptop with M3 chip, perfect for professionals',
                'price' => 2499.99,
                'quantity' => 10,
                'total' => 24999.90,
                'Category_id' => 1,
            ],
            [
                'name' => 'iPhone 15 Pro',
                'description' => 'Latest iPhone with advanced camera system',
                'price' => 999.99,
                'quantity' => 25,
                'total' => 24999.75,
                'Category_id' => 2,
            ],
            [
                'name' => 'iPad Air',
                'description' => 'Versatile tablet for work and entertainment',
                'price' => 599.99,
                'quantity' => 15,
                'total' => 8999.85,
                'Category_id' => 3,
            ],
        ];

        foreach ($productsData as $prodData) {
            $product = products::create($prodData);
            
            // Add sample images (using URLs)
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => 'https://via.placeholder.com/300x300?text=' . urlencode($product->name),
            ]);
        }
    }
}
