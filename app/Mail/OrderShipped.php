<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderShipped extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order
    ) {
        // Set locale to user's preferred language for email content
        if ($order->user && $order->user->locale) {
            $this->locale = $order->user->locale;
        } elseif (app()->getLocale()) {
            $this->locale = app()->getLocale();
        }
    }

    public function envelope(): Envelope
    {
        // Subject in user's language
        $locale = $this->locale ?? app()->getLocale();
        $subject = $locale === 'ar'
            ? 'الخبر السارّ! تم شحن الطلب رقم ' . $this->order->id . ' - متجر جيزمو'
            : 'Good News! Order #' . $this->order->id . ' Shipped - GIZMO Store';

        return new Envelope(
            subject: $subject,
            from: config('mail.from.address'),
            replyTo: [config('mail.from.address')],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-shipped',
        );
    }
}
