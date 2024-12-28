<?php

declare(strict_types=1);

namespace App\Infrastructure\Projection\Repository;

use App\Domain\BankAccountId;
use App\Infrastructure\Projection\BankAccountProjection;

interface BankAccountProjectionRepositoryInterface
{
    public function __invoke(BankAccountId $bankAccountId): ?BankAccountProjection;
}
