<?php

declare(strict_types=1);

namespace App\Api\Action;

use App\Domain\BankAccountId;
use App\Infrastructure\Projection\Repository\BankAccountProjectionRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
final readonly class GetBankAccountAction
{
    public function __construct(
        private BankAccountProjectionRepositoryInterface $bankAccountProjectionRepository,
    ) {
    }

    #[Route('/get-bank-account/{bankAccountId}', name: 'get-bank-account', methods: ['GET'])]
    public function __invoke(string $bankAccountId): Response
    {
        $bankAccountId = BankAccountId::fromString($bankAccountId);

        $bankAccountProjection = $this->bankAccountProjectionRepository->__invoke($bankAccountId);

        return new JsonResponse(
            data: $bankAccountProjection,
            status: Response::HTTP_OK
        );
    }
}
