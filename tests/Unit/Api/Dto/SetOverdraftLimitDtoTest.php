<?php

declare(strict_types=1);

namespace App\Tests\Unit\Api\Dto;

use App\Api\Dto\SetOverdraftLimitDto;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SetOverdraftLimitDto::class)]
class SetOverdraftLimitDtoTest extends TestCase
{
    public function test__construct(): void
    {
        $this->assertInstanceOf(
            expected: SetOverdraftLimitDto::class,
            actual: new SetOverdraftLimitDto(
                limit: 500
            )
        );
    }
}
