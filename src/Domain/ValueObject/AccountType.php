<?php

namespace App\Domain\ValueObject;

enum AccountType: string
{
    case SAVINGS = 'savings';
    case CHECKING = 'checking';
    case BUSINESS = 'business';
}
