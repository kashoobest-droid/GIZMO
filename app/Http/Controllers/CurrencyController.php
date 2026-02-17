<?php

namespace App\Http\Controllers;

use App\Helpers\CurrencyHelper;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    /**
     * Set the user's preferred currency
     */
    public function set(Request $request, string $currency)
    {
        // Validate the path parameter and set session (no form input expected)
        if (! CurrencyHelper::isSupportedCurrency($currency)) {
            return redirect()->back()->withErrors(['currency' => 'The selected currency is not supported.']);
        }

        session(['currency' => $currency]);

        // Store in user profile if authenticated
        if (auth()->check()) {
            auth()->user()->update(['preferred_currency' => $currency]);
        }

        return redirect()->back()->with('success', 'Currency changed to ' . CurrencyHelper::getName($currency));
    }

    /**
     * Get current currency info
     */
    public function getCurrent()
    {
        $current = CurrencyHelper::getCurrentCurrency();
        
        return response()->json([
            'currency' => $current,
            'symbol' => CurrencyHelper::getSymbol($current),
            'name' => CurrencyHelper::getName($current),
        ]);
    }
}
