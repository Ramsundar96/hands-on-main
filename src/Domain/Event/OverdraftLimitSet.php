<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\BankAccountId;

final readonly class OverdraftLimitSet
{
    public function __construct(
        public BankAccountId $bankAccountId,
        public float $newOverdraftLimit,
        public float $oldOverdraftLimit,
    ) {
    }
}
