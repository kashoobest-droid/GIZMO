<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendTestSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $phone;
    public string $message;

    public function __construct(string $phone, string $message = 'This is a queued test SMS.')
    {
        $this->phone = $phone;
        $this->message = $message;
    }

    public function handle(): void
    {
        try {
            // If you have Twilio configured, you can send here. For safety in dev we just log.
            // Example (requires Twilio client):
            // $twilio = new \Twilio\Rest\Client(config('services.twilio.sid'), config('services.twilio.token'));
            // $twilio->messages->create($this->phone, ['from' => config('services.twilio.from'), 'body' => $this->message]);

            Log::channel('payments')->info('SendTestSms (simulated) processed', ['phone' => $this->phone, 'message' => $this->message]);
        } catch (\Throwable $e) {
            Log::channel('payments')->error('SendTestSms failed', ['phone' => $this->phone, 'error' => $e->getMessage()]);
            report($e);
        }
    }
}
