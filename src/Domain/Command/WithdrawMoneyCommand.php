<?php

namespace App\Domain\Command;

class WithdrawMoneyCommand
{
    private string $bankAccountId;
    private float $amount;

    public function __construct(string $bankAccountId, float $amount)
    {
        $this->bankAccountId = $bankAccountId;
        $this->amount = $amount;
    }

    public function getBankAccountId(): string
    {
        return $this->bankAccountId;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }
}
