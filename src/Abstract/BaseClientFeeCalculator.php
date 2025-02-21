<?php

namespace App\Abstract;
use App\Interfaces\FeeCalculatorInterface;


/**
 * Abstract class BaseClientFeeCalculator
 * Provides a common structure for client fee calculators.
 */
abstract class BaseClientFeeCalculator implements FeeCalculatorInterface
{
      /**
     * Abstract method that must be implemented in derived classes.
     *
     * @param float $amount Transaction amount.
     * @param string $currency Transaction currency.
     * @param int $userId User identifier.
     * @param string $date Transaction date.
     * @return float The calculated commission fee.
     */
    abstract public function calculateFee(float $amount, string $currency, int $userId, string $date): float;
}