<?php

declare(strict_types=1);

namespace App\Domain\Command;

final readonly class SetOverdraftLimit
{
    public function __construct(
        public float $overdraftLimit,
    ) {
    }
}
