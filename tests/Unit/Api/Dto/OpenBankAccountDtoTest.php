<?php

declare(strict_types=1);

namespace App\Tests\Unit\Api\Dto;

use App\Api\Dto\OpenBankAccountDto;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(OpenBankAccountDto::class)]
class OpenBankAccountDtoTest extends TestCase
{
    public function test__construct(): void
    {
        $this->assertInstanceOf(
            expected: OpenBankAccountDto::class,
            actual: new OpenBankAccountDto(
                accountHolderName: 'Test Name',
                type: 'savings',
                currency: 'EUR'
            )
        );
    }
}
