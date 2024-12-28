<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Projection\Repository;

use App\Domain\BankAccountId;
use App\Infrastructure\Projection\BankAccountProjection;
use App\Infrastructure\Projection\Repository\BankAccountProjectionRepository;
use Doctrine\DBAL\Connection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(BankAccountProjectionRepository::class)]
class BankAccountProjectionRepositoryTest extends TestCase
{
    private BankAccountProjectionRepository $repository;
    private Connection|MockObject $connection;

    protected function setUp(): void
    {
        $this->repository = new BankAccountProjectionRepository(
            connection: $this->connection = $this->createMock(Connection::class)
        );
    }

    public function testInvoke_HasProjection_ReturnsProjection(): void
    {
        $bankAccountId = BankAccountId::fromString('c254c489-48a7-44d3-85db-02cca3e69cde');
        $projectionData = [
            'bank_account_id' => $bankAccountId->toString(),
            'account_holder_name' => 'Test Name',
            'balance' => 30,
            'account_type' => 'savings',
            'currency_code' => 'EUR',
            'overdraft_limit' => 100.0,
        ];

        $this->connection
            ->method('fetchAssociative')
            ->willReturn($projectionData);

        $projection = $this->repository->__invoke($bankAccountId);
        $this->assertEquals($projection, BankAccountProjection::fromDbalResultRow($projectionData));
    }

    public function testInvoke_DoesNotHaveProjection_ReturnsNull(): void
    {
        $bankAccountId = BankAccountId::fromString('c254c489-48a7-44d3-85db-02cca3e69cde');
        $this->connection
            ->method('fetchAssociative')
            ->willReturn(false);

        $this->assertNull($this->repository->__invoke($bankAccountId));
    }
}
