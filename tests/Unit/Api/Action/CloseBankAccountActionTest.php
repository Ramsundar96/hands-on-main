<?php

declare(strict_types=1);

namespace App\Tests\Unit\Api\Action;

use App\Api\Action\CloseBankAccountAction;
use App\Domain\BankAccount;
use App\Domain\BankAccountId;
use EventSauce\EventSourcing\AggregateRootRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

#[CoversClass(CloseBankAccountAction::class)]
class CloseBankAccountActionTest extends TestCase
{
    private AggregateRootRepository|MockObject $aggregateRootRepository;
    private CloseBankAccountAction $action;

    protected function setUp(): void
    {
        $this->action = new CloseBankAccountAction(
            aggregateRootRepository: $this->aggregateRootRepository = $this->createMock(AggregateRootRepository::class)
        );
    }

    public function test__invoke(): void
    {
        $bankAccountId = BankAccountId::fromString('b3ae961d-5d0e-4d8f-a287-54691a2828b3');
        $bankAccount = $this->createStub(BankAccount::class);
        $this->aggregateRootRepository
            ->method('retrieve')
            ->willReturn($bankAccount);

        $this->aggregateRootRepository
            ->expects($this->once())
            ->method('persist')
            ->with($this->identicalTo($bankAccount));

        $bankAccount
            ->expects($this->once())
            ->method('closeBankAccount');

        $response = $this->action->__invoke($bankAccountId->toString());
        $this->assertSame(Response::HTTP_ACCEPTED, $response->getStatusCode());

        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('bankAccountId', $content);
        $this->assertSame($bankAccountId->toString(), $content['bankAccountId']);
    }
}
