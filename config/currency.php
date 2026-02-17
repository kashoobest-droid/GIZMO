<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Currency
    |--------------------------------------------------------------------------
    |
    | The default currency for displaying prices
    |
    */
    'default' => 'SDG',
    // Which currency prices are stored in the database / admin inputs
    'prices_stored_in' => env('PRICES_STORED_IN', 'USD'),

    /*
    |--------------------------------------------------------------------------
    | Supported Currencies
    |--------------------------------------------------------------------------
    |
    | The currencies supported by the store with their symbols and exchange rates
    | Exchange rates are relative to SDG (base currency)
    |
    */
    'currencies' => [
        'SDG' => [
            'name' => 'Sudanese Dinar',
            'symbol' => 'SDG',
            'rate' => 1.0, // Base currency
        ],
        'USD' => [
            'name' => 'US Dollar',
            'symbol' => '$',
            // rate = units of USD per 1 SDG. For 1 USD = 601.50 SDG => 1 SDG = 1/601.5 USD
            'rate' => 0.0016622517,
        ],
        'EGP' => [
            'name' => 'Egyptian Pound',
            'symbol' => 'ج.م',
            'rate' => 0.5, // 1 SDG = 0.5 EGP (adjust as needed)
        ],
        'AED' => [
            'name' => 'UAE Dirham',
            'symbol' => 'د.إ',
            'rate' => 0.061, // 1 SDG = 0.061 AED (adjust as needed)
        ],
        'SAR' => [
            'name' => 'Saudi Riyal',
            'symbol' => 'ر.س',
            'rate' => 0.063, // 1 SDG = 0.063 SAR (adjust as needed)
        ],
    ],
];
