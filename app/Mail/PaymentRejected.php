<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentRejected extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order,
        public ?string $reason = null,
        public ?string $editUrl = null
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
        $locale = $this->locale ?? app()->getLocale();
        $subject = $locale === 'ar'
            ? 'تم رفض الدفع - الطلب رقم ' . $this->order->id . ' - متجر جيزمو'
            : 'Payment Rejected for Order #' . $this->order->id;

        return new Envelope(
            subject: $subject,
            from: config('mail.from.address'),
            replyTo: [config('mail.from.address')],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-rejected',
        );
    }
}
