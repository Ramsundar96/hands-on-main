<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\BankAccountId;

final readonly class BankAccountClosed
{
    public function __construct(
        public BankAccountId $bankAccountId,
    ) {
    }
}
