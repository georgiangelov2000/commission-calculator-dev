<?php
namespace App\Interfaces;

/**
 * Interface FeeCalculatorInterface
 * Defines the contract for commission fee calculation.
 */
interface FeeCalculatorInterface {
    /**
     * Calculate the commission fee for a transaction.
     *
     * @param float $amount The transaction amount.
     * @param string $currency The currency type.
     * @param int $userId The unique user ID.
     * @param string $date The transaction date.
     * @return float The calculated commission fee.
     */
    public function calculateFee(float $amount, string $currency, int $userId, string $date): float;
}