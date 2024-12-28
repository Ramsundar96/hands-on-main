<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Projector;

use App\Domain\BankAccountId;
use App\Domain\Event\BankAccountOpened;
use App\Domain\ValueObject\AccountType;
use App\Domain\ValueObject\Currency;
use App\Infrastructure\Projection\Repository\BankAccountProjectionRepository;
use App\Infrastructure\Projector\PersistBankAccountProjectionOnBankAccountOpenedProjector;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

#[CoversClass(PersistBankAccountProjectionOnBankAccountOpenedProjector::class)]
final class PersistBankAccountProjectionOnBankAccountOpenedProjectorTest extends KernelTestCase
{
    private PersistBankAccountProjectionOnBankAccountOpenedProjector $projector;
    private BankAccountProjectionRepository $projectionRepository;

    protected function setUp(): void
    {
        $this->projector = self::getContainer()->get(PersistBankAccountProjectionOnBankAccountOpenedProjector::class);
        $this->projectionRepository = self::getContainer()->get(BankAccountProjectionRepository::class);
    }

    public function testHandleBankAccountOpened(): void
    {
        $this->projector->handleBankAccountOpened(
            event: new BankAccountOpened(
                bankAccountId: $bankAccountId = BankAccountId::create(),
                accountHolderName: 'Test Account Holder Name',
                accountType: AccountType::SAVINGS,
                currency: Currency::EUR
            )
        );

        $bankAccountProjection = $this->projectionRepository->__invoke($bankAccountId);

        $this->assertNotNull($bankAccountProjection);
        $this->assertSame('Test Account Holder Name', $bankAccountProjection->accountHolderName);
        $this->assertSame(AccountType::SAVINGS->value, $bankAccountProjection->accountType);
        $this->assertSame(Currency::EUR->value, $bankAccountProjection->currency);
        $this->assertSame(0.0, $bankAccountProjection->balance);
        $this->assertSame(0.0, $bankAccountProjection->overdraftLimit);
    }
}
