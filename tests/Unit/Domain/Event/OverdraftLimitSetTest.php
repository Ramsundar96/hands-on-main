<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Event;

use App\Domain\BankAccountId;
use App\Domain\Event\OverdraftLimitSet;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(OverdraftLimitSet::class)]
class OverdraftLimitSetTest extends TestCase
{
    public function test__construct(): void
    {
        $this->assertInstanceOf(
            expected: OverdraftLimitSet::class,
            actual: new OverdraftLimitSet(
                bankAccountId: BankAccountId::create(),
                newOverdraftLimit: 250.00,
                oldOverdraftLimit: 500.00,
            )
        );
    }
}
