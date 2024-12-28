<?php

declare(strict_types=1);

namespace App\Tests\Application\GetBankAccount;

use App\Api\Action\GetBankAccountAction;
use App\Domain\BankAccountId;
use App\Domain\Event\BankAccountOpened;
use App\Domain\ValueObject\AccountType;
use App\Domain\ValueObject\Currency;
use App\Infrastructure\Projector\PersistBankAccountProjectionOnBankAccountOpenedProjector;
use App\Tests\Application\ApplicationTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

#[CoversClass(GetBankAccountAction::class)]
class GetBankAccountTest extends ApplicationTestCase
{
    private KernelBrowser $client;
    private BankAccountId $bankAccountId;

    public function setUp(): void
    {
        $this->client = self::createClient();
        static::getContainer()->get(PersistBankAccountProjectionOnBankAccountOpenedProjector::class)->handleBankAccountOpened(
            event: new BankAccountOpened(
                bankAccountId: $this->bankAccountId = BankAccountId::fromString('9dff846e-0e35-4a01-8285-64c6d5c640de'),
                accountHolderName: 'Test Name',
                accountType: AccountType::SAVINGS,
                currency: Currency::EUR
            )
        );
    }

    public function testGetBankAccount(): void
    {
        $this->client->request(
            method: 'GET',
            uri: '/get-bank-account/'.$this->bankAccountId->toString(),
        );

        $this->assertResponseIsSuccessful();

        $content = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertSame($this->bankAccountId->toString(), $content['bankAccountId']);
    }
}
