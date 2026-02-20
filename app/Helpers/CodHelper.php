<?php

namespace App\Helpers;

class CodHelper
{
    /**
     * Check if COD is available for the user based on:
     * - City must be Port Sudan (Arabic or English)
     * - Order total must be under 60,000 SDG
     *
     * @param string $city - User's city_area
     * @param float $orderTotal - Total order amount in SDG
     * @return array ['available' => bool, 'reason' => string|null]
     */
    public static function isEligible($city, $orderTotal)
    {
        $city = strtolower(trim($city ?? ''));

        // Check city is Port Sudan (English or Arabic)
        $isPortSudan = (
            (strpos($city, 'port') !== false && (strpos($city, 'sudan') !== false || strpos($city, 'portsudan') !== false)) ||
            strpos($city, 'بورتسودان') !== false
        );

        // Check order total is under 60,000 SDG
        $isUnder60k = $orderTotal < 60000;

        // Return appropriate error messages based on what fails
        if (!$isPortSudan && !$isUnder60k) {
            return [
                'available' => false,
                'reason' => 'COD is only available for Port Sudan addresses with orders under 60,000 SDG.'
            ];
        }

        if (!$isPortSudan) {
            return [
                'available' => false,
                'reason' => 'COD is only available for Port Sudan addresses.'
            ];
        }

        if (!$isUnder60k) {
            return [
                'available' => false,
                'reason' => 'COD is only available for orders under 60,000 SDG.'
            ];
        }

        return [
            'available' => true,
            'reason' => null
        ];
    }
}

