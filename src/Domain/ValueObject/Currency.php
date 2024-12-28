<?php

namespace App\Domain\ValueObject;

enum Currency: string
{
    case USD = 'USD';
    case EUR = 'EUR';
}
