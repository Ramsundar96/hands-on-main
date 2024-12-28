<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\BankAccountId;
use App\Domain\ValueObject\AccountType;
use App\Domain\ValueObject\Currency;

final readonly class BankAccountOpened
{
    public function __construct(
        public BankAccountId $bankAccountId,
        public string $accountHolderName,
        public AccountType $accountType,
        public Currency $currency,
    ) {
    }
}
