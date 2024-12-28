<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\BankAccountId;

final readonly class MoneyDeposited
{
    public function __construct(int $BankAccountId, float $amount)
    {
        $this->BankAccountId = $BankAccountId;
        $this->amount = $amount;
    }
    public function getAccountId(): int
    {
        return $this->accountId;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }
}

