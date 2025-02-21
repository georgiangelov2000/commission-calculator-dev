<?php
declare(strict_types=1);

namespace App\Services;
use App\Models\Transaction;
use App\Abstract\BaseClientFeeCalculator;
use App\Services\Clients\PrivateClientFeeCalculator;
use App\Services\Clients\BusinessClientFeeCalculator;
use Exception;

/**
 * Class CommissionCalculator
 * Manages the entire commission calculation workflow.
 */
class CommissionCalculator {
    private const DEPOSIT_FEE_MULTIPLIER = 0.0003; // Multiplier for deposit operations

    /**
     * @var array<string, BaseClientFeeCalculator> Stores registered client fee calculators.
     */
    private array $clientCalculators = [];

    /**
     * Register available client fee calculators dynamically.
     */
    public function __construct()
    {
        $this->registerDefaultCalculators();
    }

    /**
     * Register a new client type dynamically.
     *
     * @param string $clientType Client type name.
     * @param BaseClientFeeCalculator $calculator The calculator instance.
     */
    public function registerClientCalculator(string $clientType, BaseClientFeeCalculator $calculator): void
    {
        $this->clientCalculators[$clientType] = $calculator;
    }

    /**
     * Return registered client fee calculators (useful for testing or inspection).
     *
     * @return array<string, BaseClientFeeCalculator>
     */
    public function getClientFeeCalculators(): array
    {
        return $this->clientCalculators;
    }


   /**
     * Process a single transaction and return the commission fee.
     * @param Transaction $transaction The transaction to process.
     * @return float The calculated commission fee.
     * @throws Exception If an unsupported client type is encountered.
     */
    public function processTransaction(Transaction $transaction): string {
        if ($transaction->operationType === 'deposit') {
            $fee = round($transaction->amount * self::DEPOSIT_FEE_MULTIPLIER, 2);
        } else {
            // Check for the appropriate client fee calculator
            $calculator = $this->clientCalculators[$transaction->userType] ?? null;
            if (!$calculator) {
                throw new Exception("Unsupported client type: {$transaction->userType}");
            }

            $fee = $calculator->calculateFee(
                $transaction->amount,
                $transaction->currency,
                $transaction->userId,
                $transaction->date
            );
        }
        return number_format($fee, 2, '.', '');
    }

    /**
     * Read transactions from a CSV file and process them.
     *
     * @param string $filename The path to the CSV file.
     */
    public function processCSV(string $filename): array {
        $handle = fopen($filename, 'r');
        $fees = [];
        while (($data = fgetcsv($handle)) !== false) {
            $transaction = $this->parseTransactionData($data);
            $fees[] = $this->processTransaction($transaction);
        }
        fclose($handle);
        return $fees;
    }

    /**
     * Parse raw CSV data into a Transaction object.
     *
     * @param array $data The raw CSV data.
     * @return Transaction The parsed transaction object.
     * @throws Exception If the data format is invalid.
     */
    private function parseTransactionData(array $data): Transaction
    {
        if (count($data) < 6) {
            throw new Exception("Invalid transaction data. Expected 6 fields, got " . count($data));
        }

        return new Transaction(
            $data[0],            // date (string)
            $data[1],      // userId (int)
            $data[2],            // userType (string)
            $data[3],            // operationType (string)
            (float) $data[4],    // amount (convert to float)
            $data[5]             // currency (string)
        );
    }

    /**
     * Register default calculators for client types.
     */
    private function registerDefaultCalculators(): void
    {
        $this->registerClientCalculator('private', new PrivateClientFeeCalculator());
        $this->registerClientCalculator('business', new BusinessClientFeeCalculator());
    }

}