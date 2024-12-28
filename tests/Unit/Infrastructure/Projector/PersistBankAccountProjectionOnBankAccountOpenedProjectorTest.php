<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Projector;

use App\Domain\BankAccountId;
use App\Domain\Event\BankAccountOpened;
use App\Domain\ValueObject\AccountType;
use App\Domain\ValueObject\Currency;
use App\Infrastructure\Projector\PersistBankAccountProjectionOnBankAccountOpenedProjector;
use Doctrine\DBAL\Connection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(PersistBankAccountProjectionOnBankAccountOpenedProjector::class)]
class PersistBankAccountProjectionOnBankAccountOpenedProjectorTest extends TestCase
{
    private Connection|MockObject $connection;
    private PersistBankAccountProjectionOnBankAccountOpenedProjector $projector;

    protected function setUp(): void
    {
        $this->projector = new PersistBankAccountProjectionOnBankAccountOpenedProjector(
            connection: $this->connection = $this->createMock(Connection::class)
        );
    }

    public function testHandleBankAccountOpened(): void
    {
        $event = new BankAccountOpened(
            bankAccountId: $bankAccountId = BankAccountId::create(),
            accountHolderName: 'Test Name',
            accountType: AccountType::SAVINGS,
            currency: Currency::EUR,
        );

        $this->connection
            ->expects($this->once())
            ->method('insert')
            ->with(
                $this->identicalTo('bank_account_projection'),
                $this->identicalTo([
                    'bank_account_id' => $bankAccountId->toString(),
                    'account_holder_name' => 'Test Name',
                    'balance' => 0.0,
                    'account_type' => 'savings',
                    'currency_code' => 'EUR',
                    'overdraft_limit' => 0.0,
                ])
            );

        $this->projector->handleBankAccountOpened($event);
    }
}
