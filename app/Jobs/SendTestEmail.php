<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendTestEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $email;
    public string $subject;
    public string $message;

    public function __construct(string $email, string $subject = 'Test Email', string $message = 'This is a queued test email.')
    {
        $this->email = $email;
        $this->subject = $subject;
        $this->message = $message;
    }

    public function handle(): void
    {
        try {
            Mail::to($this->email)->send(new \App\Mail\TestEmail($this->subject, $this->message));
            Log::channel('payments')->info('SendTestEmail job processed', ['email' => $this->email]);
        } catch (\Throwable $e) {
            Log::channel('payments')->error('SendTestEmail job failed', ['email' => $this->email, 'error' => $e->getMessage()]);
            report($e);
        }
    }
}
