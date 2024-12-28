<?php

declare(strict_types=1);

namespace App\Api\Dto;

final readonly class SetOverdraftLimitDto
{
    public function __construct(
        public float $limit,
    ) {
    }
}
