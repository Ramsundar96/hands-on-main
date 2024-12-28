<?php

namespace App\Api\Action;

use App\Api\Dto\WithdrawMoneyDto;
use App\Domain\BankAccountId;
use App\Domain\Command\WithdrawMoneyCommand;
use EventSauce\EventSourcing\AggregateRootRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
final readonly class WithdrawMoneyAction
{
    /**
     * @param AggregateRootRepository<BankAccount> $aggregateRootRepository
     */
    public function __construct(
        private AggregateRootRepository $aggregateRootRepository,
    ) {
    }

    #[Route('/withdraw-money', name: 'withdraw_money', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] WithdrawMoneyDto $withdrawMoneyDto): Response
    {
        $bankAccountId = BankAccountId::fromString($withdrawMoneyDto->accountId);

        // Retrieve the bank account
        $bankAccount = $this->aggregateRootRepository->retrieve($bankAccountId);

        // Check if the amount is valid
        if ($withdrawMoneyDto->amount <= 0) {
            return new JsonResponse(
                data: ['error' => 'Amount must be greater than 0'],
                status: Response::HTTP_BAD_REQUEST
            );
        }

        // Create a WithdrawMoneyCommand
        $withdrawMoneyCommand = new WithdrawMoneyCommand(
            $bankAccountId,
            $withdrawMoneyDto->amount
        );

        // Apply the withdrawal command
        try {
            $bankAccount->withdrawMoney($withdrawMoneyCommand);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(
                data: ['error' => $e->getMessage()],
                status: Response::HTTP_BAD_REQUEST
            );
        }

        // Persist the updated bank account
        $this->aggregateRootRepository->persist($bankAccount);

        return new JsonResponse(
            data: ['success' => 'Withdrawal successful'],
            status: Response::HTTP_OK
        );
    }
}
