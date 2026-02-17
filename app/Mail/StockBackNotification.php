<?php

namespace App\Mail;

use App\Models\products;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StockBackNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public products $product,
        public string $email
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            to: [$this->email],
            subject: config('app.name') . ' - ' . $this->product->name . ' is back in stock!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.stock-back-notification',
            with: [
                'product' => $this->product,
                'productUrl' => route('product.show', $this->product),
                'storeName' => config('app.name'),
            ],
        );
    }
}
