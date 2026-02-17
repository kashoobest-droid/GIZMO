<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show()
    {
        return view('contact');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string|max:2000',
        ]);

        try {
            Mail::raw(
                "From: {$request->name} <{$request->email}>\n\nMessage:\n{$request->message}",
                function ($message) use ($request) {
                    $message->to(config('mail.from.address'))
                        ->replyTo($request->email, $request->name)
                        ->subject('Contact form: ' . config('app.name'));
                }
            );
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'We could not send your message. Please try again or email us directly.');
        }

        return back()->with('success', 'Thank you! We have received your message and will get back to you soon.');
    }
}
