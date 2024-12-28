<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain;

use App\Domain\BankAccountId;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

#[CoversClass(BankAccountId::class)]
class BankAccountIdTest extends TestCase
{
    public function testCreate(): void
    {
        $bankAccountId = BankAccountId::create();
        $this->assertInstanceOf(BankAccountId::class, $bankAccountId);
        $this->assertTrue(Uuid::isValid($bankAccountId->id));
    }

    public function testToString(): void
    {
        $bankAccountId = BankAccountId::fromString('fcc06615-b9d4-4cb1-96b5-f4c98319f5b1');
        $this->assertSame('fcc06615-b9d4-4cb1-96b5-f4c98319f5b1', $bankAccountId->toString());
    }

    public function testFromString(): void
    {
        $bankAccountId = BankAccountId::fromString('fcc06615-b9d4-4cb1-96b5-f4c98319f5b1');
        $this->assertInstanceOf(BankAccountId::class, $bankAccountId);
        $this->assertSame('fcc06615-b9d4-4cb1-96b5-f4c98319f5b1', $bankAccountId->toString());
    }
}
