<?php
declare(strict_types=1);

namespace App\Infrastructure\Projector;

use App\Domain\Event\MoneyDeposited;
use Doctrine\DBAL\Connection;
use EventSauce\EventSourcing\EventConsumption\EventConsumer;

final class PersistBankAccountProjectionOnMoneyDepositedProjector extends EventConsumer
{
    public function __construct(
        private readonly Connection $connection,
    ) {
    }

    public function handleMoneyDeposited(MoneyDeposited $event): void
    {
        // Update the balance in the projection
        $this->connection->update(
            table: 'bank_account_projection',
            data: [
                'balance' => $event->amount + $this->getCurrentBalance($event->bankAccountId),
            ],
            where: [
                'bank_account_id' => $event->bankAccountId->toString(),
            ]
        );
    }

    private function getCurrentBalance($bankAccountId): float
    {
        // Fetch current balance for the bank account from the database
        $result = $this->connection->fetchAssociative(
            'SELECT balance FROM bank_account_projection WHERE bank_account_id = ?',
            [$bankAccountId->toString()]
        );

        return $result['balance'] ?? 0.0; // Return 0.0 if not found
    }
}

