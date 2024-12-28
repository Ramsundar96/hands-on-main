<?php

declare(strict_types=1);

namespace App\Api\Action;

use App\Domain\BankAccount;
use App\Domain\BankAccountId;
use App\Domain\Command\CloseBankAccount;
use EventSauce\EventSourcing\AggregateRootRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
final readonly class CloseBankAccountAction
{
    /**
     * @param AggregateRootRepository<BankAccount> $aggregateRootRepository
     */
    public function __construct(
        private AggregateRootRepository $aggregateRootRepository,
    ) {
    }

    #[Route('/close-bank-account/{bankAccountId}', name: 'close-bank-account', methods: ['POST'])]
    public function __invoke(string $bankAccountId): Response
    {
        $bankAccountId = BankAccountId::fromString($bankAccountId);

        $bankAccount = $this->aggregateRootRepository->retrieve($bankAccountId);
        $bankAccount->closeBankAccount(
            command: new CloseBankAccount()
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
