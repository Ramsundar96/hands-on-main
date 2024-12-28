<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Command\CloseBankAccount;
use App\Domain\Command\OpenBankAccount;
use App\Domain\Command\SetOverdraftLimit;
use App\Domain\Event\BankAccountClosed;
use App\Domain\Event\BankAccountOpened;
use App\Domain\Event\depositMoney;
use App\Domain\Event\MoneyDeposited;
use App\Domain\Event\OverdraftLimitSet;
use App\Domain\Event\withdrawalamountSet;
use App\Domain\Exception\CannotCloseBankAccountBecauseAccountIsNotActive;
use App\Domain\ValueObject\AccountStatus;
use App\Domain\ValueObject\AccountType;
use App\Domain\ValueObject\Currency;
use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootBehaviour;

/**
 * @implements AggregateRoot<BankAccountId>
 */
class BankAccount implements AggregateRoot
{
    /**
     * @use AggregateRootBehaviour<BankAccountId>
     */
    use AggregateRootBehaviour;

    private string $accountHolderName;
    private float $balance = 0;
    private AccountType $type;
    private Currency $currency;
    private float $overdraftLimit = 0;
    private AccountStatus $status;
    public const NAME = 'money.deposited';

    private $BankAccountId;
    private $amount;

    public static function openBankAccount(OpenBankAccount $command): self
    {
        $bankAccount = new self($command->bankAccountId);
        $bankAccount->recordThat(
            event: new BankAccountOpened(
                bankAccountId: $command->bankAccountId,
                accountHolderName: $command->accountHolderName,
                accountType: $command->accountType,
                currency: $command->currency,
            )
        );

        return $bankAccount;
    }

    public function getAccountHolderName(): string
    {
        return $this->accountHolderName;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function getType(): AccountType
    {
        return $this->type;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function getOverdraftLimit(): float
    {
        return $this->overdraftLimit;
    }

    public function getStatus(): AccountStatus
    {
        return $this->status;
    }

    public function setOverdraftLimit(SetOverdraftLimit $command): void
    {
        $this->recordThat(
            event: new OverdraftLimitSet(
                bankAccountId: $this->aggregateRootId(),
                newOverdraftLimit: $command->overdraftLimit,
                oldOverdraftLimit: $this->overdraftLimit
            )
        );
    }

    public function closeBankAccount(CloseBankAccount $command): void
    {
        if (AccountStatus::ACTIVE !== $this->status) {
            throw new CannotCloseBankAccountBecauseAccountIsNotActive();
        }
        $this->recordThat(
            event: new BankAccountClosed(
                bankAccountId: $this->aggregateRootId(),
            )
        );
    }

    /**
     * @phpstan-ignore method.unused (Used by \EventSauce\EventSourcing\AggregateAlwaysAppliesEvents)
     */
    private function applyBankAccountOpened(BankAccountOpened $event): void
    {
        $this->accountHolderName = $event->accountHolderName;
        $this->type = $event->accountType;
        $this->currency = $event->currency;
        $this->status = AccountStatus::ACTIVE;
    }

     /**
     * @phpstan-ignore method.unused (Used by \EventSauce\EventSourcing\AggregateAlwaysAppliesEvents)
     */
    public function depositMoney(depositMoney $event): void
    {
        if ($event->amount <= 0) {
            throw new \InvalidArgumentException("Deposit amount must be positive");
        }

        $this->balance += $event->amount; // Update balance on deposit
        $this->recordThat(new MoneyDeposited(
            $this->aggregateRootId(),
            $event->amount
        ));
    }

    /**
     * @phpstan-ignore method.unused (Used by \EventSauce\EventSourcing\AggregateAlwaysAppliesEvents)
     */
    private function applyOverdraftLimitSet(OverdraftLimitSet $event): void
    {
        $this->overdraftLimit = $event->newOverdraftLimit;
    }

    /**
     * @phpstan-ignore method.unused (Used by \EventSauce\EventSourcing\AggregateAlwaysAppliesEvents)
     */
    private function withdrawMoney(withdrawalamountSet $event): void
    {
        $this->withdrawalamount = $event->newwithdrawalamount;
    }

    /**
     * @phpstan-ignore method.unused (Used by \EventSauce\EventSourcing\AggregateAlwaysAppliesEvents)
     */
    private function applyBankAccountClosed(BankAccountClosed $event): void
    {
        $this->status = AccountStatus::CLOSED;
    }

    /**
     * @phpstan-ignore method.unused (Used by \EventSauce\EventSourcing\AggregateAlwaysAppliesEvents)
     */
    private function applyMoneyDeposited(MoneyDeposited $event): void
    {
        $this->balance += $event->amount; // Increase the balance
    }

}
