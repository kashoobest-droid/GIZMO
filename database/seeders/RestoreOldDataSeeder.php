<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class RestoreOldDataSeeder extends Seeder
{
    public function run()
    {
        DB::beginTransaction();
        try {
            // Paste your categories here as associative arrays.
            // Example item:
            // ['id' => 1, 'name' => 'Phones', 'slug' => 'phones', 'created_at' => '2024-01-01 00:00:00', 'updated_at' => '2024-01-01 00:00:00']
            $categories = [
                ['name' => 'Electronics', 'slug' => 'electronics', 'description' => 'Phones, computers, TVs and consumer electronics', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
                ['name' => 'Mobile Phones', 'slug' => 'mobile-phones', 'description' => 'Smartphones and mobile accessories', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
                ['name' => 'Laptops & Computers', 'slug' => 'laptops-computers', 'description' => 'Laptops, desktops and computer components', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
                ['name' => 'Computer Components', 'slug' => 'computer-components', 'description' => 'CPUs, GPUs, motherboards, RAM, storage', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
                ['name' => 'Monitors & Displays', 'slug' => 'monitors-displays', 'description' => 'Monitors and display accessories', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
                ['name' => 'Tablets', 'slug' => 'tablets', 'description' => 'Tablets and e-readers', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
                ['name' => 'Accessories', 'slug' => 'accessories', 'description' => 'Chargers, cables, cases and general accessories', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
                ['name' => 'Audio & Headphones', 'slug' => 'audio-headphones', 'description' => 'Headphones, speakers and audio equipment', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
                ['name' => 'Cameras', 'slug' => 'cameras', 'description' => 'Digital cameras, lenses and photography gear', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
                ['name' => 'TV & Video', 'slug' => 'tv-video', 'description' => 'Televisions, projectors and streaming devices', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
                ['name' => 'Gaming', 'slug' => 'gaming', 'description' => 'Consoles, games, and gaming accessories', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
                ['name' => 'Networking', 'slug' => 'networking', 'description' => 'Routers, switches and networking gear', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
                ['name' => 'Drones & RC', 'slug' => 'drones-rc', 'description' => 'Drones, quadcopters and RC devices', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
                ['name' => 'Printers & Scanners', 'slug' => 'printers-scanners', 'description' => 'Printers, scanners and consumables', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
                ['name' => 'Smart Home', 'slug' => 'smart-home', 'description' => 'Home automation and IoT devices', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
                ['name' => 'Wearables', 'slug' => 'wearables', 'description' => 'Smartwatches and fitness trackers', 'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
            ];

            foreach ($categories as $cat) {
                // Only keep columns that actually exist on the table (migration has: name, description, timestamps)
                $fields = Arr::only($cat, ['name', 'description', 'created_at', 'updated_at']);

                if (isset($cat['id']) && DB::table('categories')->where('id', $cat['id'])->exists()) {
                    DB::table('categories')->where('id', $cat['id'])->update($fields);
                } else {
                    DB::table('categories')->insert($fields);
                }
            }

            // Paste your products here as associative arrays.
            // Example item:
            // ['id' => 1, 'category_id' => 1, 'name' => 'iPhone', 'sku' => 'IP-1', 'price' => 599.99, 'created_at' => '2024-01-01 00:00:00', 'updated_at' => '2024-01-01 00:00:00']
            $products = [
                // Phones
                ['name' => 'Samsung Galaxy S25 Ultra', 'price' => 1500.00, 'quantity' => 7, 'category_name' => 'Mobile Phones', 'description' => 'Flagship Samsung smartphone with cutting-edge camera system, large AMOLED display and top-tier performance for multitasking and gaming. Includes fast charging and premium build.'],
                ['name' => 'iPhone 17 Pro Max', 'price' => 1200.00, 'quantity' => 19, 'category_name' => 'Mobile Phones', 'description' => 'Apple’s latest Pro Max model with industry-leading performance, advanced photography features, and superior ecosystem integration.' ],
                ['name' => 'iPhone 15 Pro', 'price' => 999.99, 'quantity' => 15, 'category_name' => 'Mobile Phones', 'description' => 'Powerful and compact iPhone with great camera capabilities and long battery life. Currently part of seasonal promotions.' ],

                // Laptops
                ['name' => 'Acer Nitro V16', 'price' => 2900.00, 'quantity' => 1, 'category_name' => 'Laptops & Computers', 'description' => 'High-performance gaming laptop with a high-refresh display, dedicated GPU, and advanced cooling—ideal for gamers and creators.' ],
                ['name' => 'HP Victus 16', 'price' => 1500.00, 'quantity' => 3, 'category_name' => 'Laptops & Computers', 'description' => 'Affordable gaming laptop with solid performance for everyday gaming and productivity tasks.' ],
                ['name' => 'MacBook Pro 16 (M3)', 'price' => 2499.99, 'quantity' => 8, 'category_name' => 'Laptops & Computers', 'description' => 'Apple MacBook Pro with M3 chip delivering exceptional CPU and GPU performance, excellent battery life and a brilliant Retina display.' ],
                ['name' => 'Alienware M18', 'price' => 2999.00, 'quantity' => 0, 'category_name' => 'Laptops & Computers', 'description' => 'Top-tier desktop-replacement gaming laptop with extreme performance; currently out of stock.' ],

                // Tablets
                ['name' => 'iPad Air', 'price' => 599.99, 'quantity' => 0, 'category_name' => 'Tablets', 'description' => 'Lightweight Apple tablet with powerful performance for media, learning and light productivity; currently out of stock.' ],
            ];

            foreach ($products as $p) {
                $catName = $p['category_name'] ?? 'Electronics';
                $categoryId = DB::table('categories')->where('name', $catName)->value('id');

                $record = [
                    'name' => $p['name'],
                    'description' => $p['description'] ?? '',
                    'price' => $p['price'],
                    'quantity' => $p['quantity'],
                    'total' => ($p['price'] * max(1, $p['quantity'])),
                    'Category_description' => $catName,
                    'Category_id' => $categoryId ?: 1,
                    'created_at' => now()->toDateTimeString(),
                    'updated_at' => now()->toDateTimeString(),
                ];

                if (isset($p['id']) && DB::table('products')->where('id', $p['id'])->exists()) {
                    DB::table('products')->where('id', $p['id'])->update($record);
                } else {
                    DB::table('products')->insert($record);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
