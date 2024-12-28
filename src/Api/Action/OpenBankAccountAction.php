<?php

declare(strict_types=1);

namespace App\Api\Action;

use App\Api\Dto\OpenBankAccountDto;
use App\Domain\BankAccount;
use App\Domain\BankAccountId;
use App\Domain\Command\OpenBankAccount;
use App\Domain\ValueObject\AccountType;
use App\Domain\ValueObject\Currency;
use EventSauce\EventSourcing\AggregateRootRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
final readonly class OpenBankAccountAction
{
    /**
     * @param AggregateRootRepository<BankAccount> $aggregateRootRepository
     */
    public function __construct(
        private AggregateRootRepository $aggregateRootRepository,
    ) {
    }

    #[Route('/open-bank-account', name: 'open_bank_account', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] OpenBankAccountDto $openBankAccountDto): Response
    {
        $bankAccountId = BankAccountId::create();

        $bankAccount = BankAccount::openBankAccount(
            command: new OpenBankAccount(
                bankAccountId: $bankAccountId,
                accountHolderName: $openBankAccountDto->accountHolderName,
                accountType: AccountType::from($openBankAccountDto->type),
                currency: Currency::from($openBankAccountDto->currency),
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
