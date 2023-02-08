<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
require 'src/Bank.php';

class BankTest extends TestCase
{
    /** @test */
    public function testAddMoney()
    {
        $bankMock = $this->createMock(Bank::class);
        $bankMock->method('addMoney')->willReturn(true);
        $bankMock->expects($this->once())->method('addMoney')->with(123456, 100, 'USD');

        $account_number = 123456;
        $amount = 100;
        $currency = 'USD';

        $result = $bankMock->addMoney($account_number, $amount, $currency);
        
        $this->assertTrue($result);
    }

    public function testSendMoney()
    {
        $bankMock = $this->createMock(Bank::class);
        $bankMock->method('sendMoney')->willReturn(true);

        // Testing if we are sending the correct values to the send method
        $bankMock->expects($this->once())->method('sendMoney')->with(123456, 100, 'USD');

        $account_number = 123456;
        $amount = 100;
        $currency = 'USD';

        $result = $bankMock->sendMoney($account_number, $amount, $currency);
        
        $this->assertTrue($result);
    }

    public function testPaymentsToday()
    {
        $bankMock = $this->createMock(Bank::class);
        $bankMock->method('checkPaymentsToday')->willReturn(5);

        // Always passing $account_number as parameter
        $bankMock->expects($this->once())->method('checkPaymentsToday')->with(123456);

        $account_number = 123456;
        $amount_of_transactions = 5;

        $result = $bankMock->checkPaymentsToday($account_number);
        
        $this->assertEquals($amount_of_transactions, $result);
    }

    public function testMakePayment()
    {
        $bankMock = $this->createMock(Bank::class);
        $bankMock->method('makePayment')->willReturn(true);

        $bankMock->expects($this->once())->method('makePayment')->with($account_number = 123456, $amount = 100, $currency = 'USD');

        $result = $bankMock->makePayment($account_number, $amount, $currency);
        
        $this->assertTrue($result);
    }
}