<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Command;

use App\Domain\Command\SetOverdraftLimit;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SetOverdraftLimit::class)]
class SetOverdraftLimitTest extends TestCase
{
    public function test__construct(): void
    {
        $this->assertInstanceOf(
            expected: SetOverdraftLimit::class,
            actual: new SetOverdraftLimit(
                overdraftLimit: 500.00
            )
        );
    }
}
