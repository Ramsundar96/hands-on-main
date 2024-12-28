<?php

declare(strict_types=1);

namespace App\Api\Action;

use App\Api\Dto\DepositMoneyDto;
use App\Domain\BankAccountId;
use App\Domain\Command\DepositMoneyCommand;
use EventSauce\EventSourcing\AggregateRootRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
final readonly class DepositMoneyAction
{
    /**
     * @param AggregateRootRepository<BankAccount> $aggregateRootRepository
     */
    public function __construct(
        private AggregateRootRepository $aggregateRootRepository,
    ) {
    }

    #[Route('/deposit-money', name: 'deposit_money', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] DepositMoneyDto $depositMoneyDto): Response
    {
        $bankAccountId = BankAccountId::fromString($depositMoneyDto->accountId);

        // Retrieve the bank account
        $bankAccount = $this->aggregateRootRepository->retrieve($bankAccountId);

        // Check if the amount is valid
        if ($depositMoneyDto->amount <= 0) {
            return new JsonResponse(
                data: ['error' => 'Amount must be greater than 0'],
                status: Response::HTTP_BAD_REQUEST
            );
        }

        // Create a DepositMoneyCommand
        $depositMoneyCommand = new DepositMoneyCommand(
            $bankAccountId,
            $depositMoneyDto->amount
        );

        // Apply the deposit command
        $bankAccount->depositMoney($depositMoneyCommand);

        // Persist the updated bank account
        $this->aggregateRootRepository->persist($bankAccount);

        return new JsonResponse(
            data: ['success' => 'Deposit successful'],
            status: Response::HTTP_OK
        );
    }
}


