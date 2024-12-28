<?php

declare(strict_types=1);

namespace App\Tests\Unit\Api\Action;

use App\Api\Action\GetBankAccountAction;
use App\Domain\BankAccountId;
use App\Infrastructure\Projection\BankAccountProjection;
use App\Infrastructure\Projection\Repository\BankAccountProjectionRepositoryInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;

#[CoversClass(GetBankAccountAction::class)]
class GetBankAccountActionTest extends TestCase
{
    private GetBankAccountAction $action;
    private BankAccountProjectionRepositoryInterface|Stub $bankAccountProjectionRepository;

    protected function setUp(): void
    {
        $this->action = new GetBankAccountAction(
            bankAccountProjectionRepository: $this->bankAccountProjectionRepository = $this->createStub(
                BankAccountProjectionRepositoryInterface::class
            )
        );
    }

    public function test__invoke(): void
    {
        $bankAccountId = BankAccountId::create();
        $bankAccountProjection = new BankAccountProjection(
            bankAccountId: $bankAccountId->toString(),
            accountHolderName: 'TestName',
            balance: 3500.00,
            accountType: 'savings',
            currency: 'EUR',
            overdraftLimit: 500.00
        );
        $this->bankAccountProjectionRepository
            ->method('__invoke')
            ->willReturn($bankAccountProjection);

        $response = $this->action->__invoke($bankAccountId->toString());
        $this->assertSame(json_encode($bankAccountProjection), $response->getContent());
    }
}
