<?php

declare(strict_types=1);
namespace App\Tests\Entity;

use App\Entity\BankAccount;
use App\Event\MoneyWithdrawnEvent;
use App\Event\OverdraftUsedEvent;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class WithDrawalAmountTest extends TestCase
{
    private $eventDispatcher;

    protected function setUp(): void
    {
        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
    }

    public function testWithdrawWithinBalance()
    {
        $account = new BankAccount(100.00, 50.00, $this->eventDispatcher);

        $this->eventDispatcher->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(MoneyWithdrawnEvent::class));

        $this->assertTrue($account->withdrawMoney(50.00));
        $this->assertEquals(50.00, $account->getBalance());
    }

    public function testWithdrawWithinOverdraftLimit()
    {
        $account = new BankAccount(100.00, 50.00, $this->eventDispatcher);

        $this->eventDispatcher->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(MoneyWithdrawnEvent::class));

        $this->eventDispatcher->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(OverdraftUsedEvent::class));

        $this->assertTrue($account->withdrawMoney(140.00));
        $this->assertEquals(-40.00, $account->getBalance());
    }

    public function testWithdrawExceedingLimit()
    {
        $account = new BankAccount(100.00, 50.00, $this->eventDispatcher);

        $this->assertFalse($account->withdrawMoney(200.00)); // exceeds balance + overdraft
        $this->assertEquals(100.00, $account->getBalance());
    }

    public function testWithdrawNegativeAmount()
    {
        $this->expectException(\InvalidArgumentException::class);
        $account = new BankAccount(100.00, 50.00, $this->eventDispatcher);
        $account->withdrawMoney(-10.00);
    }
}
