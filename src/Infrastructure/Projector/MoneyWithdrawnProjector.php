<?php
namespace App\Infrastructure\Projection\Projector;

use App\Domain\Event\MoneyWithdrawn;
use App\Infrastructure\Projection\BankAccountProjection;
use App\Infrastructure\Persistence\BankAccountProjectionRepository;

class MoneyWithdrawnProjector
{
    private BankAccountProjectionRepository $projectionRepository;

    public function __construct(BankAccountProjectionRepository $projectionRepository)
    {
        $this->projectionRepository = $projectionRepository;
    }

    public function __invoke(MoneyWithdrawn $event): void
    {
        $projection = $this->projectionRepository->findById($event->bankAccountId());

        if ($projection === null) {
            // Handle case where the projection does not exist
            throw new \RuntimeException("Projection not found for bank account ID {$event->bankAccountId()}");
        }

        // Update the balance
        $projection->updateBalance(-$event->amount()); // Withdraw is negative

        // Save the updated projection
        $this->projectionRepository->save($projection);
    }
}
