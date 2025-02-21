<?php
declare(strict_types=1);

require 'vendor/autoload.php';

use App\Services\CommissionCalculator;

/**
 * Entry point of the commission fee calculator.
 * 
 * This script:
 * - Reads a CSV file containing transactions.
 * - Processes each transaction.
 * - Outputs the commission fee for each transaction.
 * 
 * Usage:
 * ```
 * php script.php input.csv
 * ```
 * 
 * @param string $argv[1] The path to the input CSV file.
 */

try {
    if ($argc < 2) {
        throw new \Exception("Usage: php script.php <path-to-csv-file>");
    }

    // Get the CSV filename from command-line arguments
    $csvFile = $argv[1];

    // Check if the file exists
    if (!file_exists($csvFile)) {
        throw new \Exception("Error: File not found - $csvFile");
    }

    // Initialize the Commission Calculator
    $calculator = new CommissionCalculator();

    // Process the CSV file and output the commission fees
    $fees = $calculator->processCSV($csvFile);
    foreach ($fees as $fee) {
        echo $fee . PHP_EOL;
    }
    
} catch (\Exception $e) {
    // Handle errors and display meaningful messages
    echo "Exception: " . $e->getMessage() . PHP_EOL;
    exit(1);
}