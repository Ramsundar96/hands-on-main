<?php

declare(strict_types=1);

namespace App\Tests\Unit\Api\Action;

use App\Api\Action\OpenBankAccountAction;
use App\Api\Dto\OpenBankAccountDto;
use App\Domain\BankAccount;
use EventSauce\EventSourcing\AggregateRootRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;

#[CoversClass(OpenBankAccountAction::class)]
class OpenBankAccountActionTest extends TestCase
{
    private AggregateRootRepository|MockObject $aggregateRootRepository;
    private OpenBankAccountAction $action;

    protected function setUp(): void
    {
        $this->action = new OpenBankAccountAction(
            aggregateRootRepository: $this->aggregateRootRepository = $this->createMock(AggregateRootRepository::class)
        );
    }

    public function test__invoke(): void
    {
        $openBankAccountDto = new OpenBankAccountDto(
            accountHolderName: 'Test Name',
            type: 'savings',
            currency: 'EUR'
        );

        $this->aggregateRootRepository
            ->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(BankAccount::class));

        $response = $this->action->__invoke($openBankAccountDto);

        $this->assertSame(Response::HTTP_ACCEPTED, $response->getStatusCode());

        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('bankAccountId', $content);
        $this->assertTrue(Uuid::isValid($content['bankAccountId']));
    }
}
