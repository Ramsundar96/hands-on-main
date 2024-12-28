<?php
namespace App\Infrastructure\Projection\Projector;

use App\Domain\Event\OverdraftUsed;
use App\Infrastructure\Projection\BankAccountProjection;
use App\Infrastructure\Persistence\BankAccountProjectionRepository;

class OverdraftUsedProjector
{
    private BankAccountProjectionRepository $projectionRepository;

    public function __construct(BankAccountProjectionRepository $projectionRepository)
    {
        $this->projectionRepository = $projectionRepository;
    }

    public function __invoke(OverdraftUsed $event): void
    {
        $projection = $this->projectionRepository->findById($event->bankAccountId());

        if ($projection === null) {
            // Handle case where the projection does not exist
            throw new \RuntimeException("Projection not found for bank account ID {$event->bankAccountId()}");
        }

        // Update the overdraft limit
        $projection->updateOverdraftLimit($event->newOverdraftLimit());

        // Save the updated projection
        $this->projectionRepository->save($projection);
    }
}
