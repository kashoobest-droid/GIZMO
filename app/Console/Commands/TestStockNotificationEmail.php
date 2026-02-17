<?php

namespace App\Console\Commands;

use App\Mail\StockBackNotification;
use App\Models\products;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestStockNotificationEmail extends Command
{
    protected $signature = 'test:stock-email {product_id} {email}';
    protected $description = 'Test sending a stock notification email';

    public function handle()
    {
        $productId = $this->argument('product_id');
        $email = $this->argument('email');

        $product = products::find($productId);
        
        if (!$product) {
            $this->error("Product with ID {$productId} not found.");
            return 1;
        }

        $this->info("Sending test email for product: {$product->name}");
        $this->info("To: {$email}");

        try {
            Mail::send(new StockBackNotification($product, $email));
            $this->info('✓ Email sent successfully!');
            return 0;
        } catch (\Exception $e) {
            $this->error('✗ Email failed to send.');
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }
    }
}
