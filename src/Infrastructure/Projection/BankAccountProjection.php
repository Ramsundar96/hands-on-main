<?php

declare(strict_types=1);

namespace App\Infrastructure\Projection;

final readonly class BankAccountProjection
{
    public function __construct(
        public string $bankAccountId,
        public string $accountHolderName,
        public float $balance,
        public string $accountType,
        public string $currency,
        public float $overdraftLimit,
    ) {
    }

    /**
     * @param array<mixed> $row
     */
    public static function fromDbalResultRow(array $row): self
    {
        return new self(
            bankAccountId: $row['bank_account_id'],
            accountHolderName: $row['account_holder_name'],
            balance: $row['balance'],
            accountType: $row['account_type'],
            currency: $row['currency_code'],
            overdraftLimit: $row['overdraft_limit'],
        );
    }

    public function updateBalance(float $amount): void
    {
        $this->balance += $amount;
    }

    public function updateOverdraftLimit(float $newLimit): void
    {
        $this->overdraftLimit = $newLimit;
    }
}
