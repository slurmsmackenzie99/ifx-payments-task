<?php

class Bank
{
    private $conn;
    public $connTest;

    public function __construct()
    {
        $this->conn = mysqli_connect("localhost", "root", "", "bank");

        if (!$this->conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    }

    /*
    Add money to the account
    */
    public function addMoney($account_number, $amount, $currency)
    {
        $account_number = mysqli_real_escape_string($this->conn, $account_number);
        $amount = mysqli_real_escape_string($this->conn, $amount);
        $currency = mysqli_real_escape_string($this->conn, $currency);

        $sql = "UPDATE bank_accounts SET balance = balance + ? WHERE account_number = ? AND currency = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $amount, $account_number, $currency);
        mysqli_stmt_execute($stmt);

        $sql = "INSERT INTO transactions (account_number, transaction_type, amount, currency, transaction_date)
                VALUES (?, 'credit', ?, ?, NOW())";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $account_number, $amount, $currency);
        mysqli_stmt_execute($stmt);
    }

    /*
    Handles sending the money, if currency mismatch or not enough money throw Exception
    */
    public function sendMoney($account_number, $amount, $currency)
    {
        $account_number = mysqli_real_escape_string($this->conn, $account_number);
        $amount = mysqli_real_escape_string($this->conn, $amount);
        $currency = mysqli_real_escape_string($this->conn, $currency);

        // Get the value of currency from $account_number
        $get_currency_sql = "SELECT currency FROM bank_accounts WHERE account_number = ?";
        $stmt = mysqli_prepare($this->conn, $get_currency_sql);
        mysqli_stmt_bind_param($stmt, "s", $account_number);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        $db_currency = $row['currency'];

        // Compare the currency from the parameters with the one in the database
        if ($currency !== $db_currency) {
            throw new Exception("Currency mismatch: expected $currency, got $db_currency");
        }

        // Add 0.5% of transaction cost
        $transaction_cost = $amount * 0.005;
        $sql = "SELECT balance FROM bank_accounts WHERE account_number = ? AND currency = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $account_number, $currency);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        $balance = $row['balance'];

        if ($balance >= ($amount + $transaction_cost)) {
            $sql = "UPDATE bank_accounts SET balance = balance - ? - ? WHERE account_number = ? AND currency = ?";
            $stmt = mysqli_prepare($this->conn, $sql);
            mysqli_stmt_bind_param($stmt, "ddss", $amount, $transaction_cost, $account_number, $currency);
            mysqli_stmt_execute($stmt);
            
            
            $sql = "INSERT INTO transactions (account_number, transaction_type, amount, currency, transaction_cost, transaction_date)
        VALUES (?, 'debit', ?, ?, ?, NOW())";
            $stmt = mysqli_prepare($this->conn, $sql);
            mysqli_stmt_bind_param($stmt, "sdsd", $account_number, $amount, $currency, $transaction_cost);
            mysqli_stmt_execute($stmt);
        } else {
            throw new Exception("Insufficient balance");
        }
    }

    /*
    Checks the amount of payments that a given account has at a given day
    */
    public function checkPaymentsToday($account_number)
    {
        $sql = "SELECT COUNT(*) as payments_today FROM transactions WHERE account_number = '$account_number' AND transaction_date >= CURDATE() AND transaction_type = 'debit'";
        $result = mysqli_query($this->conn, $sql);
        $row = mysqli_fetch_assoc($result);
        return $row['payments_today'];
    }

    /*
    If the account made less than 3 payments that day then try and send the money
    */
    public function makePayment($account_number, $amount, $currency)
    {
        $payments_today = $this->checkPaymentsToday($account_number);
        if ($payments_today < 3) {
            $this->sendMoney(
                $account_number,
                $amount,
                $currency
            );
            return true;
        } else {
            return false;
        }
    }
}
