<?php

namespace App\Jobs;

use App\Mail\StockBackNotification;
use App\Models\products;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class NotifyStockBackJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public products $product
    ) {}

    public function handle(): void
    {
        if ($this->product->quantity < 1) {
            return;
        }

        $notifications = $this->product->stockNotifications()->where('notified', false)->get();

        foreach ($notifications as $notification) {
            try {
                Mail::send(new StockBackNotification($this->product, $notification->email));
                $notification->update(['notified' => true]);
            } catch (\Throwable $e) {
                \Log::error('Stock notification email failed for ' . $notification->email . ': ' . $e->getMessage());
                report($e);
            }
        }
    }
}
