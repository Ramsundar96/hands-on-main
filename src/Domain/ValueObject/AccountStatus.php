<?php

namespace App\Domain\ValueObject;

enum AccountStatus: string
{
    case ACTIVE = 'active';
    case CLOSED = 'closed';
    case MoneyDeposited = 'MoneyDeposited';
}
