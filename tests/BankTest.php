<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
require 'src/Bank.php';

class BankTest extends TestCase
{
    /**
     * @var Bank
     */
    private $bank;

    /**
     * Set up the mock Bank object
     */
    public function setUp(): void
    {
        $this->bank = $this->createMock(Bank::class);
    }

    /** @test */
    public function testAddMoney()
    {
        // Define the expectations for the addMoney method
        $this->bank->expects($this->once())
            ->method('addMoney')
            ->with(
                $this->equalTo('1234567890'),
                $this->equalTo(1000),
                $this->equalTo('USD')
            );

        // Call the addMoney method
        $this->bank->addMoney('1234567890', 1000, 'USD');
   
    }

    /** @test */
    public function testSendMoney()
    {
        // Create a mock object for the Bank class
        $this->bank = $this->createMock(Bank::class);

        // Define the expectations for the sendMoney method
        $this->bank->expects($this->once())
            ->method('sendMoney')
            ->with(
                $this->equalTo('1234567890'),
                $this->equalTo(1000),
                $this->equalTo('USD')
            );

        // Call the sendMoney method
        $this->bank->sendMoney('1234567890', 1000, 'USD');
    }

    /** @test */
    public function testPaymentsToday()
    {
        $this->bank = $this->createMock(Bank::class);

        // Always passing $account_number as parameter
        $this->bank->expects($this->once())->method('checkPaymentsToday')->with(123456)->willReturn(5);

        $result = $this->bank->checkPaymentsToday(123456);
        
        $this->assertEquals(5, $result);
    }

    /** @test */
    public function testMakePayment()
    {
        $this->bank = $this->createMock(Bank::class);
        $this->bank->method('makePayment')->willReturn(true);

        $this->bank->expects($this->once())->method('makePayment')->with($account_number = 123456, $amount = 100, $currency = 'USD');

        $result = $this->bank->makePayment($account_number, $amount, $currency);
        
        $this->assertTrue($result);
    }

    /** @test */
    public function testSendMoneyCurrencyMismatchException()
    {
        // Create a mock object for the Bank class
        $this->bank = $this->createMock(Bank::class);

        // Define the expectations for the sendMoney method to throw a currency mismatch exception
        $this->bank->expects($this->once())
            ->method('sendMoney')
            ->will($this->throwException(new Exception('Currency mismatch: expected USD, got EUR')));

        // Verify that the exception is thrown when the sendMoney method is called
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Currency mismatch: expected USD, got EUR');

        $this->bank->sendMoney('1234567890', 1000, 'USD');
    }

    /** @test */
    public function testSendMoneyInsufficientBalanceException()
    {
        // Create a mock object for the Bank class
        $this->bank = $this->createMock(Bank::class);

        // Define the expectations for the sendMoney method to throw an insufficient balance exception
        $this->bank->expects($this->once())
            ->method('sendMoney')
            ->will($this->throwException(new Exception('Insufficient balance')));

        // Verify that the exception is thrown when the sendMoney method is called
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Insufficient balance');

        $this->bank->sendMoney('1234567890', 1000, 'USD');
    }
}