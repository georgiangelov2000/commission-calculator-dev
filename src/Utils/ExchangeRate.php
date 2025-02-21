<?php
declare(strict_types=1);

namespace App\Utils;

/**
 * Class ExchangeRateService
 * Handles exchange rate conversions using Paysera API.
 */
class ExchangeRate {
    private static array $rates = [];
    private static string $apiUrl = 'https://developers.paysera.com/tasks/api/currency-exchange-rates';

    /**
     * Prevent instantiation
     */
    private function __construct() {}

    /**
     * Load exchange rates from Paysera API if not cached.
     */
    private static function loadRates(): void {
        if (empty(self::$rates)) {
            $response = file_get_contents(self::$apiUrl);
            $data = json_decode($response, true);

            if (isset($data['rates'])) {
                self::$rates = $data['rates'];
            }
        }
    }

    /**
     * Get exchange rate for a currency.
     *
     * @param string $currency Currency code (e.g., USD, JPY).
     * @return float The exchange rate relative to EUR.
     */
    public static function getRate(string $currency): float {
        self::loadRates();
        return self::$rates[$currency] ?? 1.0;
    }

    /**
     * Convert an amount from a given currency to EUR.
     *
     * @param float $amount The amount to convert.
     * @param string $currency The source currency.
     * @return float The converted amount in EUR.
     */
    public static function convertToEur(float $amount, string $currency): float {
        $rate = self::getRate($currency);
        return ($currency === 'EUR') ? round($amount, 2) : round($amount / $rate, 2);
    }

    /**
     * Convert an amount from EUR to a given currency.
     *
     * @param float $amount The amount in EUR.
     * @param string $currency The target currency.
     * @return float The converted amount in the target currency.
     */
    public static function convertFromEur(float $amount, string $currency): float {
        $rate = self::getRate($currency);
        return ($currency === 'EUR') ? round($amount, 2) : round($amount * $rate, 2);
    }
}