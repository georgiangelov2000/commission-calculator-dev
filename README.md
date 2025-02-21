# 📁 Commission Fee Calculator

A PHP application that calculates **commission fees** for deposits and withdrawals based on business rules.

## ✨ Features
- Processes transactions from CSV files
- Calculates fees based on client type and transaction rules
- Handles multi-currency transactions
- Implements exchange rate conversion
- Follows clean architecture principles
- PSR-4 autoloading and PSR-12 coding standards
- Automated testing with PHPUnit

---

## 💾 Installation

### **1. Clone the Repository**
```sh
git clone https://github.com/georgiangelov2000/commission-calculator-dev.git
cd commission-calculator-dev
```

### **2. Install Dependencies**
```sh
composer install
```

### **3. Make the Script Executable**
```sh
chmod +x script.php
```

---

## 🔄 Usage

### **Run the application with a CSV file**
```sh
php script.php input.csv
```

### **Example Input File (`input.csv`)**
```
2014-12-31,4,private,withdraw,1200.00,EUR
2015-01-01,4,private,withdraw,1000.00,EUR
2016-01-05,4,private,withdraw,1000.00,EUR
2016-01-05,1,private,deposit,200.00,EUR
2016-01-06,2,business,withdraw,300.00,EUR
```

---

## 📂 Project Structure
```
commission-calculator/
├── src/
|   ├── Abstract/             # Abstract classes
│   │   ├── BaseClientFeeCalculator.php # This file (Parent class for fee calculation)
│   ├── Models/               # Data models
│   │   ├── Transaction.php
│   ├── Interfaces/           # Interface definitions
│   │   ├── FeeCalculatorInterface.php # Defines contract for fee calculators
│   ├── Services/             # Business logic (services)
│   ├── Services/Clients/        # Specific client fee calculators
│   │   ├── PrivateClientFeeCalculator.php  # Private Client Fee Calculation
│   │   ├── BusinessClientFeeCalculator.php # Business Client Fee Calculation
│   │   ├── CommissionCalculator.php
│   ├── Utils/                # Utility functions
│   │   ├── CurrencyFormatter.php
    |   ├── ExchangeRate.php
├── tests/                    # Unit tests
│   ├── CommissionCalculatorTest.php
├── input.csv                 # Sample CSV file
├── script.php                # Entry point
├── composer.json             # Composer configuration
├── phpunit.xml               # PHPUnit configuration
├── README.md                 # Documentation
```

---

## Important Note on Exchange Rates
- The results may differ from those in the GitHub repository due to real-time exchange rate fluctuations. The example output in the repository was calculated using the following rates:
The example output in the repository was calculated using the following rates:
EUR:USD - 1:1.1497
EUR:JPY - 1:129.53
However, since this project fetches live exchange rates from the API, the results will vary depending on the current market rates.

## 📈 Code Explanation

### **1. Models**
- `Transaction.php` → Represents a financial transaction.

### **2. Interfaces**
- `FeeCalculatorInterface.php` → Defines a contract for all fee calculators.

### **3. Services**
- `Clients/PrivateClientFeeCalculator.php` → Handles withdrawal fee calculations for private clients.
- `Clients/BusinessClientFeeCalculator.php` → Handles withdrawal fee calculations for business clients.
- `CommissionCalculator.php` → Orchestrates the fee calculation process.

### **4. Utilities**
- `CurrencyFormatter.php` → Rounds commission fees according to currency decimal places.
- `ExchangeRate.php` → Fetches exchange rates for currency conversion.

### **5. Script**
- `script.php` → Reads CSV file and processes transactions.

---

## 🧪 Testing

### **Run Tests**
```sh
vendor/bin/phpunit tests/CommissionCalculatorTest.php
```

