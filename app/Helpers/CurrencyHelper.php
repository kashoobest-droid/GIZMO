<?php

namespace App\Helpers;

class CurrencyHelper
{
    /**
     * Get the current currency from session or default
     */
    public static function getCurrentCurrency(): string
    {
        return session('currency', config('currency.default'));
    }

    /**
     * Set the current currency in session
     */
    public static function setCurrency(string $currency): void
    {
        if (self::isSupportedCurrency($currency)) {
            session(['currency' => $currency]);
        }
    }

    /**
     * Check if a currency is supported
     */
    public static function isSupportedCurrency(string $currency): bool
    {
        return isset(config('currency.currencies')[$currency]);
    }

    /**
     * Convert a price from the stored currency to the target currency.
     * Uses configured rates which are relative to SDG (rate = units of currency per 1 SDG).
     */
    public static function convertPrice(float $price, string $toCurrency = null): float
    {
        $toCurrency = $toCurrency ?? self::getCurrentCurrency();

        if (!self::isSupportedCurrency($toCurrency)) {
            $toCurrency = config('currency.default');
        }

        $storedCurrency = config('currency.prices_stored_in', config('currency.default'));
        if (! self::isSupportedCurrency($storedCurrency)) {
            $storedCurrency = config('currency.default');
        }

        // rates are defined as: rate(currency) = units_of_currency_per_1_SDG
        $rateStored = config('currency.currencies.' . $storedCurrency . '.rate', 1);
        $rateTarget = config('currency.currencies.' . $toCurrency . '.rate', 1);

        // To convert: price_in_target = price_in_stored * (rateTarget / rateStored)
        $converted = $price * ($rateTarget / $rateStored);

        return round($converted, 2);
    }

    /**
     * Get the currency symbol
     */
    public static function getSymbol(string $currency = null): string
    {
        $currency = $currency ?? self::getCurrentCurrency();
        return config('currency.currencies.' . $currency . '.symbol', 'SDG');
    }

    /**
     * Get the currency name
     */
    public static function getName(string $currency = null): string
    {
        $currency = $currency ?? self::getCurrentCurrency();
        return config('currency.currencies.' . $currency . '.name', 'Sudanese Dinar');
    }

    /**
     * Format a price with currency symbol and converted value
     */
    public static function formatPrice(float $price, string $currency = null): string
    {
        $currency = $currency ?? self::getCurrentCurrency();
        $convertedPrice = self::convertPrice($price, $currency);
        $symbol = self::getSymbol($currency);

        return $symbol . number_format($convertedPrice, 2);
    }

    /**
     * Get all supported currencies
     */
    public static function getAllCurrencies(): array
    {
        return config('currency.currencies');
    }
}
