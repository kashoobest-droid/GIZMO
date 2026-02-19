<?php

namespace App\Services;

use Twilio\Rest\Client;
use App\Models\Verification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TwilioService
{
    protected $client;

    public function __construct()
    {
        if (class_exists('\Twilio\Rest\Client')) {
            $this->client = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
        }
    }

    

    /**
     * Send OTP with Throttling Security
     *
     * @throws \Exception when throttled
     */
    public function sendOtp($phone)
    {
        // Respect configured SMS driver (default to 'log') to avoid calling Twilio in trials
        $driver = env('SMS_DRIVER', 'log');
        if ($driver !== 'twilio') {
            $code = rand(100000, 999999);
            Verification::create([
                'phone' => $phone,
                'code' => $code,
                'expires_at' => now()->addMinutes(10),
                'attempts' => 0,
            ]);
            Log::info("OTP generated for {$phone} (not logged).", ['note' => 'SMS_DRIVER != twilio']);
            return true;
        }

        // Anti-Spam: Check if an OTP was sent in the last 60 seconds
        $lastSent = Verification::where('phone', $phone)
            ->where('created_at', '>', now()->subMinute())
            ->first();

        if ($lastSent) {
            throw new \Exception('Please wait 60 seconds before requesting a new code.');
        }

        $code = rand(100000, 999999);

        // Store in DB
        Verification::create([
            'phone' => $phone,
            'code' => $code,
            'expires_at' => now()->addMinutes(10),
            'attempts' => 0,
        ]);

        // Send via Twilio if available, otherwise log for demo
        try {
            if (! $this->client) {
                Log::info("OTP generated for {$phone} (Twilio client not configured).", ['note' => 'no-client']);
                return true;
            }

            // Prefer Messaging Service (handles geo/sender routing) if configured
            $msid = env('TWILIO_MESSAGING_SID');
            if ($msid) {
                return $this->client->messages->create($phone, [
                    'messagingServiceSid' => $msid,
                    'body' => "Your GIZMO Store verification code is: {$code}. It expires in 10 minutes.",
                ]);
            }

            // Otherwise send from configured TWILIO_NUMBER (no extra validation)
            $from = env('TWILIO_NUMBER');
            if ($from) {
                return $this->client->messages->create($phone, [
                    'from' => $from,
                    'body' => "Your GIZMO Store verification code is: {$code}. It expires in 10 minutes.",
                ]);
            }

            // Twilio client present but no messaging service or from number: log and fallback
            Log::info("OTP generated for {$phone} (no messaging service or from number configured).", ['note' => 'no-msid-or-from']);
            return true;
        } catch (\Twilio\Exceptions\RestException $re) {
            // Twilio REST exception gives helpful diagnostics
            $err = sprintf('Twilio REST error: %s (code: %s)', $re->getMessage(), $re->getCode());
            Log::error($err, ['phone' => $phone]);
            throw new \Exception('SMS provider error');
        } catch (\Throwable $e) {
            Log::error('Twilio sendOtp error', ['phone' => $phone, 'error' => $e->getMessage()]);
            throw new \Exception('SMS provider error');
        }
    }

    /**
     * Verify OTP with Attempt Limiting
     */
    public function verifyOtp($phone, $code)
    {
        $otp = Verification::where('phone', $phone)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (! $otp) {
            return false;
        }

        // Security: prevent brute force
        if ($otp->attempts >= 100) {
            return false;
        }

        if ($otp->code !== $code) {
            $otp->increment('attempts');
            return false;
        }

        // Success: remove the OTP record
        $otp->delete();
        return true;
    }
}
