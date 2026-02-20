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
    'prices_stored_in' => env('PRICES_STORED_IN', 'SDG'),

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
            'name' => 'Sudanese Pound',
            'symbol' => 'ج.س',
            'rate' => 1.0, // Base currency
        ],
        'EGP' => [
            'name' => 'Egyptian Pound',
            'symbol' => 'ج.م',
            'rate' => 0.5, // 1 SDG = 0.5 EGP (adjust as needed)
        ],
    ],
];
