<?php

declare(strict_types=1);

namespace App\Tests\Unit\Api\Action;

use App\Api\Action\SetOverdraftLimitAction;
use App\Api\Dto\SetOverdraftLimitDto;
use App\Domain\BankAccount;
use App\Domain\BankAccountId;
use EventSauce\EventSourcing\AggregateRootRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

#[CoversClass(SetOverdraftLimitAction::class)]
class SetOverdraftLimitActionTest extends TestCase
{
    private AggregateRootRepository|MockObject $aggregateRootRepository;
    private SetOverdraftLimitAction $action;

    protected function setUp(): void
    {
        $this->action = new SetOverdraftLimitAction(
            aggregateRootRepository: $this->aggregateRootRepository = $this->createMock(AggregateRootRepository::class)
        );
    }

    public function test__invoke(): void
    {
        $bankAccountId = BankAccountId::fromString('d88f53d5-8eed-47bc-a5c3-3a7aef8c4962');

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
            ->method('setOverdraftLimit');

        $setOverdraftLimitDto = new SetOverdraftLimitDto(
            limit: 500
        );

        $response = $this->action->__invoke(
            setOverdraftLimitDto: $setOverdraftLimitDto,
            bankAccountId: $bankAccountId->toString()
        );
        $this->assertSame(Response::HTTP_ACCEPTED, $response->getStatusCode());

        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('bankAccountId', $content);
        $this->assertSame($bankAccountId->toString(), $content['bankAccountId']);
    }
}
