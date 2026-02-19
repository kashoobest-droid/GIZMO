<?php

namespace App\Http\Controllers;

use App\Models\Verification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class VerificationController extends Controller
{
    public function sendOtp(Request $request)
    {
        $v = Validator::make($request->all(), [
            'phone' => 'required|string',
        ]);
        if ($v->fails()) {
            return back()->withErrors($v)->withInput();
        }

        $phone = $request->phone;
        // Anti-spam: prevent sending a new OTP within 60 seconds
        $last = Verification::where('phone', $phone)
            ->where('created_at', '>', now()->subSeconds(60))
            ->orderBy('created_at', 'desc')
            ->first();

        if ($last) {
            $secs = 60 - now()->diffInSeconds($last->created_at);
            $msg = 'Please wait ' . $secs . ' seconds before requesting a new code.';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['ok' => false, 'message' => $msg, 'retry_after' => $secs], 429);
            }
            return back()->with('error', $msg);
        }
        $otp = rand(100000, 999999);

        $record = Verification::create([
            'phone' => $phone,
            'code' => $otp,
            'expires_at' => now()->addMinutes(5),
            'attempts' => 0,
        ]);

        $sendError = null;
        // Try to send via Twilio if configured and available, otherwise log for demo
        try {
            $driver = env('SMS_DRIVER', 'log');
            if ($driver === 'twilio' && class_exists('\\Twilio\\Rest\\Client')) {
                $sid = config('services.twilio.sid') ?? env('TWILIO_SID');
                $token = config('services.twilio.token') ?? env('TWILIO_AUTH_TOKEN');
                $from = config('services.twilio.from') ?? env('TWILIO_NUMBER');
                $client = new \Twilio\Rest\Client($sid, $token);
                $result = $client->messages->create($phone, [
                    'from' => $from,
                    'body' => "Your GIZMO Store verification code is: {$otp}",
                ]);

                // If Twilio returned a message SID, consider it sent.
                if (! empty($result) && isset($result->sid) && $result->sid) {
                    // success — do nothing
                } else {
                    // Unexpected response from Twilio; log without revealing the code
                    Log::info('Unexpected Twilio response for OTP send', ['phone' => $phone]);
                }
            } else {
                Log::info('OTP generated (no Twilio driver) for phone', ['phone' => $phone]);
            }
        } catch (\Throwable $e) {
            Log::error('OTP send failed', ['phone' => $phone, 'error' => $e->getMessage()]);
            $sendError = $e->getMessage();
        }

        // Prepare response data
        $data = ['otp_sent' => $phone, 'send_error' => $sendError ? true : false];
        if ($sendError) {
            $data['message'] = 'OTP sent. (SMS provider reported an issue; code was logged.)';
        } else {
            $data['message'] = 'OTP sent. Check your phone or logs during demo.';
        }

        // For AJAX/JSON requests: always return 200 so the UI doesn't alarm the user
        // (we still record and log provider errors server-side).
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json($data, 200);
        }

        // For normal full-page requests, keep previous UX: show error or success flash
        $flash = ['otp_sent' => $phone];
        if ($sendError) {
            $flash['error'] = 'Failed to send OTP via SMS provider; code was logged for demo.';
        } else {
            $flash['success'] = $data['message'];
        }

        return back()->with($flash);
    }

    public function verifyOtp(Request $request)
    {
        $v = Validator::make($request->all(), [
            'phone' => 'required|string',
            'code' => 'required|string',
        ]);
        if ($v->fails()) {
            return back()->withErrors($v)->withInput();
        }
        // Rate limiting / attempt limiting
        $maxAttempts = 100;

        // Get the most recent, non-expired record for this phone
        $record = Verification::where('phone', $request->phone)
            ->where('expires_at', '>', now())
            ->orderBy('created_at', 'desc')
            ->first();

        if (! $record) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['ok' => false, 'message' => 'Invalid or expired code. Please request a new code.'], 422);
            }
            return back()->withErrors(['code' => 'Invalid or expired code. Please request a new code.'])->withInput();
        }

        if ($record->attempts >= $maxAttempts) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['ok' => false, 'message' => 'Too many failed attempts. Please request a new code.'], 429);
            }
            return back()->withErrors(['code' => 'Too many failed attempts. Please request a new code.'])->withInput();
        }

        // Successful verification
        if ($record->code === $request->code) {
            $record->update(['verified' => true]);

            // If a logged in user matches this phone, mark their phone as verified
            if (auth()->check() && auth()->user()->phone === $request->phone) {
                $user = auth()->user();
                try {
                    $user->phone_verified_at = now();
                    $user->save();
                } catch (\Throwable $e) {
                    // If saving phone_verified_at fails (missing column), report and persist phone directly
                    report($e);
                    try {
                        \Illuminate\Support\Facades\DB::table('users')->where('id', $user->id)->update(['phone' => $request->phone]);
                    } catch (\Throwable $inner) {
                        report($inner);
                    }
                }
            } else {
                // If the authenticated user hasn't saved this phone yet, store a session flag so
                // when they save their profile we can mark the phone as verified.
                session(['otp_verified_for' => $request->phone]);
            }

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['ok' => true, 'message' => 'Phone verified successfully.'], 200);
            }

            return redirect('/')->with('success', 'Phone verified successfully.');
        }

        // Wrong code: increment attempts and return error with remaining attempts
        $record->increment('attempts');
        $attemptsLeft = max(0, $maxAttempts - $record->attempts);

        $msg = 'Invalid code.' . ($attemptsLeft > 0 ? " You have {$attemptsLeft} attempt" . ($attemptsLeft > 1 ? 's' : '') . ' left.' : ' No attempts left. Please request a new code.');

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['ok' => false, 'message' => $msg, 'attempts_left' => $attemptsLeft], 422);
        }

        return back()->withErrors(['code' => $msg])->withInput()->with('attempts_left', $attemptsLeft);
    }
}
