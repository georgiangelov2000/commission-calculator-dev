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
    public function calculateFee(float $amount, string $currency, int $userId, string $date): float {
        $weekKey = date('oW', strtotime($date)) . '-' . $userId;
        
        // Convert to EUR first to ensure accurate comparison
        $amountInEur = ExchangeRate::convertToEur($amount, $currency);

        if (!isset($this->weeklyWithdrawals[$weekKey])) {
            $this->weeklyWithdrawals[$weekKey] = ['total' => 0, 'count' => 0];
        }
                
        // Free withdrawals check (up to 1000 EUR, first 3 transactions)
        if ($this->weeklyWithdrawals[$weekKey]['count'] < 3 && $this->weeklyWithdrawals[$weekKey]['total'] + $amountInEur <= 1000) {
            $this->weeklyWithdrawals[$weekKey]['total'] += $amount;
            $this->weeklyWithdrawals[$weekKey]['count']++;
            return 0;
        }
               
        // If limit exceeded, only charge for the excess amount
        $excessAmount = max(0, ($this->weeklyWithdrawals[$weekKey]['total'] + $amountInEur) - 1000);
        $feeInEur = ($excessAmount > 0) ? $excessAmount * 0.003 : 0;

        $this->weeklyWithdrawals[$weekKey]['total'] += $amount;
        $this->weeklyWithdrawals[$weekKey]['count']++;

        // Convert fee back to original currency
        $finalFee = ExchangeRate::convertFromEur($feeInEur, $currency);

        return CurrencyFormatter::round($finalFee, $currency);
    }

}