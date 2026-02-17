<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        NewsletterSubscriber::updateOrCreate(
            ['email' => $request->email],
            ['subscribed' => true]
        );

        return back()->with('newsletter_success', 'Thanks for subscribing! We\'ll keep you updated.');
    }
}
