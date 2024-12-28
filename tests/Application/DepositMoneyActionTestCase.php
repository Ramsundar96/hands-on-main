<?php

namespace App\Tests\Api\Action;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DepositMoneyActionTest extends WebTestCase
{
    public function testDepositMoney()
    {
        $client = static::createClient();

        $client->request('POST', '/deposit-money', [
            'accountId' => 'some-account-id',
            'amount' => 100
        ]);

        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains(['success' => 'Deposit successful']);
    }

    public function testWithdrawMoney()
    {
        $client = static::createClient();

        $client->request('POST', '/withdraw-money', [
            'accountId' => 'some-account-id',
            'amount' => 50
        ]);

        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains(['success' => 'Withdrawal successful']);
    }
}
