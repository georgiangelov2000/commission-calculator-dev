<?php

namespace App\Utils;

/**
 * Utility class for currency-related operations, such as formatting or rounding amounts.
 */
class CurrencyFormatter
{
    private function __construct() {} // Prevent instantiation
    /**
     * Rounds a given amount based on the specified currency.
     *
     * - For "JPY" (Japanese Yen), the amount is rounded to 0 decimal places as Yen does not use fractional units.
     * - For other currencies, the amount is rounded to 2 decimal places.
     *
     * @param float  $amount   The amount of money to be rounded.
     * @param string $currency The currency code (e.g., "USD", "JPY").
     * 
     * @return float The rounded amount, using PHP_ROUND_HALF_UP rounding mode.
     */
    public static function round(float $amount, string $currency): float
    {
        // Determine the number of decimal places based on the currency. JPY uses 0, others use 2.
        $decimalPlaces = ($currency === "JPY") ? 0 : 2;

        // Round the amount to the appropriate decimal places using PHP rounding mode PHP_ROUND_HALF_UP.
        return round($amount, $decimalPlaces, PHP_ROUND_HALF_UP);
    }
}