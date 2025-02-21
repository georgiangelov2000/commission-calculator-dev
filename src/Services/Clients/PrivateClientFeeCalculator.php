<?php
declare(strict_types=1);

namespace App\Services\Clients;

use App\Abstract\BaseClientFeeCalculator;
use App\Utils\CurrencyFormatter;
use App\Utils\ExchangeRate;

/**
 * Class PrivateClientFeeCalculator
 * Implements fee calculation logic for private clients.
 */
class PrivateClientFeeCalculator extends BaseClientFeeCalculator
{
    // Commission fee rate for private clients (0.3%)
    private const COMMISSION_RATE = 0.003;

    // Keep track of weekly withdrawals for each user in a specific week
    private array $weeklyWithdrawals = [];

    /**
     * Calculate the commission fee for a private client.
     *
     * - The first 3 withdrawals of the week are free up to 1000 EUR.
     * - If the total withdrawn exceeds 1000 EUR, the excess is charged at 0.3%.
     * - Subsequent withdrawals in the same week are fully charged at 0.3%.
     *
     * @param float $amount Transaction amount.
     * @param string $currency Transaction currency.
     * @param int $userId User identifier.
     * @param string $date Transaction date.
     * @return float The commission fee.
     */
    public function calculateFee(float $amount, string $currency, int $userId, string $date): float
    {
        // Unique key for the user and week (ISO-8601 year and week number)
        $weekKey = date('oW', strtotime($date)) . '-' . $userId;

        // Convert the transaction amount to EUR
        $amountInEur = ExchangeRate::convertToEur($amount, $currency);

        // Initialize weekly data if not already present
        if (!isset($this->weeklyWithdrawals[$weekKey])) {
            $this->weeklyWithdrawals[$weekKey] = ['total' => 0, 'count' => 0];
        }

        // Reference to the current week's data for the user
        $weeklyData = &$this->weeklyWithdrawals[$weekKey];

        // The commission fee in EUR
        $feeInEur = 0;

        // Free withdrawals logic (within the 3 free transactions and up to 1000 EUR limit)
        if ($weeklyData['count'] < 3) {
            $remainingFreeAmount = max(0, 1000 - $weeklyData['total']);
            if ($amountInEur <= $remainingFreeAmount) {
                // Transaction is entirely within free limits
                $weeklyData['total'] += $amountInEur;
                $weeklyData['count']++;
                return 0.0; // No fee charged
            } else {
                // Partial free amount, charge the excess
                $feeInEur = ($amountInEur - $remainingFreeAmount) * self::COMMISSION_RATE;
                $weeklyData['total'] += $remainingFreeAmount; // Add the free part
            }
        } else {
            // All withdrawals after the 3 free transactions are charged fully
            $feeInEur = $amountInEur * self::COMMISSION_RATE;
        }

        // Update the user's weekly data
        $weeklyData['total'] += $amountInEur;
        $weeklyData['count']++;

        // Convert the fee back to the original currency
        $finalFee = ExchangeRate::convertFromEur($feeInEur, $currency);

        // Return the rounded fee
        return CurrencyFormatter::round($finalFee, $currency);
    }
}