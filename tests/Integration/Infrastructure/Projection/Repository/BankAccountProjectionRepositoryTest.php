<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Projection\Repository;

use App\Domain\BankAccountId;
use App\Domain\Event\BankAccountOpened;
use App\Domain\ValueObject\AccountType;
use App\Domain\ValueObject\Currency;
use App\Infrastructure\Projection\BankAccountProjection;
use App\Infrastructure\Projection\Repository\BankAccountProjectionRepository;
use App\Infrastructure\Projector\PersistBankAccountProjectionOnBankAccountOpenedProjector;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

#[CoversClass(BankAccountProjectionRepository::class)]
class BankAccountProjectionRepositoryTest extends KernelTestCase
{
    private PersistBankAccountProjectionOnBankAccountOpenedProjector $projector;
    private BankAccountProjectionRepository $projectionRepository;

    protected function setUp(): void
    {
        $this->projector = self::getContainer()->get(PersistBankAccountProjectionOnBankAccountOpenedProjector::class);
        $this->projectionRepository = self::getContainer()->get(BankAccountProjectionRepository::class);
    }

    public function testInvoke_HasProjection_ReturnsProjection(): void
    {
        $this->projector->handleBankAccountOpened(
            event: new BankAccountOpened(
                bankAccountId: $bankAccountId = BankAccountId::fromString('7e13a3bd-f789-4493-9b3a-0ff6945b76c4'),
                accountHolderName: 'Test Account Holder Name',
                accountType: AccountType::SAVINGS,
                currency: Currency::EUR
            )
        );

        $this->assertEquals(
            expected: new BankAccountProjection(
                bankAccountId: $bankAccountId->toString(),
                accountHolderName: 'Test Account Holder Name',
                balance: 0,
                accountType: 'savings',
                currency: 'EUR',
                overdraftLimit: 0
            ),
            actual: $this->projectionRepository->__invoke($bankAccountId)
        );
    }

    public function testInvoke_DoesNotHaveProjection_ReturnsNull(): void
    {
        $this->assertNull($this->projectionRepository->__invoke(BankAccountId::fromString('136b2fe5-7acb-49bb-874c-67724e6a6483')));
    }
}
