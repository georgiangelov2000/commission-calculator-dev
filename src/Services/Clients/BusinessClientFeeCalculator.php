<?php
declare(strict_types=1);

namespace App\Services\Clients;
use App\Abstract\BaseClientFeeCalculator;
use App\Utils\CurrencyFormatter;

/**
 * Class BusinessClientFeeCalculator
 * Implements fee calculation logic for business clients.
 */
class BusinessClientFeeCalculator extends BaseClientFeeCalculator {
    /**
     * Calculate the commission fee for a business client.
     * Business clients are charged a flat 0.5% commission on all withdrawals.
     *
     * @param float $amount Transaction amount.
     * @param string $currency Transaction currency.
     * @param int $userId User identifier.
     * @param string $date Transaction date.
     * @return float The commission fee.
     */
    public function calculateFee(float $amount, string $currency, int $userId, string $date): float {
        return CurrencyFormatter::round($amount * 0.005, $currency);
    }
}