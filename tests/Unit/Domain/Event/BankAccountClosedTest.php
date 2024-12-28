<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Event;

use App\Domain\BankAccountId;
use App\Domain\Event\BankAccountClosed;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(BankAccountClosed::class)]
class BankAccountClosedTest extends TestCase
{
    public function test__construct(): void
    {
        $this->assertInstanceOf(
            expected: BankAccountClosed::class,
            actual: new BankAccountClosed(
                bankAccountId: BankAccountId::create()
            )
        );
    }
}
