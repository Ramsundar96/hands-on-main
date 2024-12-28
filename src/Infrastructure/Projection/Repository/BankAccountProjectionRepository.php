<?php

declare(strict_types=1);

namespace App\Infrastructure\Projection\Repository;

use App\Domain\BankAccountId;
use App\Infrastructure\Projection\BankAccountProjection;
use Doctrine\DBAL\Connection;

final readonly class BankAccountProjectionRepository implements BankAccountProjectionRepositoryInterface
{
    public function __construct(
        private Connection $connection,
    ) {
    }

    public function __invoke(BankAccountId $bankAccountId): ?BankAccountProjection
    {
        $query = <<<'EOD'
            SELECT * FROM bank_account_projection WHERE bank_account_id = :bankAccountId
EOD;
        $result = $this->connection->fetchAssociative(
            query: $query,
            params: [
                'bankAccountId' => $bankAccountId->toString(),
            ]
        );

        if (false === $result) {
            return null;
        }

        return BankAccountProjection::fromDbalResultRow($result);
    }
}
