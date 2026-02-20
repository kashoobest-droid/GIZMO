<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use Illuminate\Mail\Mailables\Attachment;

class PaymentApproved extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public ?string $invoicePdf;
    public ?string $invoiceFilename;

    public function __construct(
        public Order $order,
        ?string $invoicePdf = null,
        ?string $invoiceFilename = 'invoice.pdf'
    ) {
        $this->invoicePdf = $invoicePdf;
        $this->invoiceFilename = $invoiceFilename;

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
            ? 'تم قبول الدفع - الطلب رقم ' . $this->order->id . ' - متجر جيزمو'
            : 'Payment Approved for Order #' . $this->order->id;

        return new Envelope(
            subject: $subject,
            from: config('mail.from.address'),
            replyTo: [config('mail.from.address')],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-approved',
        );
    }

    public function attachments(): array
    {
        if (empty($this->invoicePdf)) {
            return [];
        }

        $data = $this->invoicePdf;
        // If invoicePdf is base64 encoded, decode it for attachment
        if (base64_encode(base64_decode($data, true)) === $data) {
            $data = base64_decode($data);
        }

        return [
            Attachment::fromData(fn() => $data)
                ->as($this->invoiceFilename)
                ->withMime('application/pdf'),
        ];
    }
}
