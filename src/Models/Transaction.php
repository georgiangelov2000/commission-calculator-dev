<?php

namespace App\Models;

/**
 * Class Transaction
 * Represents a financial transaction from the CSV file.
 */
class Transaction 
{
 public string $date;
 public int $userId;
 public string $userType;
 public string $operationType;
 public float $amount;
 public string $currency;
 
 /**
     * Transaction constructor.
     *
     * @param string $date The date of the transaction.
     * @param int $userId The unique identifier of the user.
     * @param string $userType The type of user (private/business).
     * @param string $operationType The type of operation (deposit/withdraw).
     * @param float $amount The amount of money involved.
     * @param string $currency The currency in which the transaction is made.
     */
 public function __construct(string $date, string $userId, string $userType, string $operationType, float $amount, string $currency) {
     $this->date = $date;
     $this->userId = $userId;
     $this->userType = $userType;
     $this->operationType = $operationType;
     $this->amount = $amount;
     $this->currency = $currency;
 }
}