<?php

declare(strict_types=1);

namespace App\Api\Action;

use App\Api\Dto\SetOverdraftLimitDto;
use App\Domain\BankAccount;
use App\Domain\BankAccountId;
use App\Domain\Command\SetOverdraftLimit;
use EventSauce\EventSourcing\AggregateRootRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
final readonly class SetOverdraftLimitAction
{
    /**
     * @param AggregateRootRepository<BankAccount> $aggregateRootRepository
     */
    public function __construct(
        private AggregateRootRepository $aggregateRootRepository,
    ) {
    }

    #[Route('/set-overdraft-limit/{bankAccountId}', name: 'set_overdraft_limit', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] SetOverdraftLimitDto $setOverdraftLimitDto, string $bankAccountId): Response
    {
        $bankAccountId = BankAccountId::fromString($bankAccountId);

        $bankAccount = $this->aggregateRootRepository->retrieve($bankAccountId);
        $bankAccount->setOverdraftLimit(
            command: new SetOverdraftLimit(
                overdraftLimit: $setOverdraftLimitDto->limit
            )
        );

        $this->aggregateRootRepository->persist($bankAccount);

        return new JsonResponse(
            data: [
                'bankAccountId' => $bankAccountId->toString(),
            ],
            status: Response::HTTP_ACCEPTED
        );
    }
}
