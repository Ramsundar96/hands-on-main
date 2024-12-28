<?php

namespace App\Infrastructure\Projection\Projector;

use App\Domain\Event\MoneyDeposited;
use App\Infrastructure\Projection\BankAccountProjection;
use App\Infrastructure\Persistence\BankAccountProjectionRepository;

class MoneyDepositedProjector
{
    private BankAccountProjectionRepository $projectionRepository;

    public function __construct(BankAccountProjectionRepository $projectionRepository)
    {
        $this->projectionRepository = $projectionRepository;
    }

    public function __invoke(MoneyDeposited $event): void
    {
        $projection = $this->projectionRepository->findById($event->bankAccountId());

        if ($projection === null) {
            // Handle case where the projection does not exist (create new or throw error)
            // Here, we're assuming the projection already exists when a deposit occurs.
            throw new \RuntimeException("Projection not found for bank account ID {$event->bankAccountId()}");
        }

        // Update the balance
        $projection->updateBalance($event->amount());

        // Save the updated projection
        $this->projectionRepository->save($projection);
    }
}
