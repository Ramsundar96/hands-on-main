<?php

declare(strict_types=1);

namespace App\Api\Dto;

final readonly class OpenBankAccountDto
{
    public function __construct(
        public string $accountHolderName,
        public string $type,
        public string $currency,
    ) {
    }
}
