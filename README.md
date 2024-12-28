# Hands-On Coding Test: Bank Account System

## Introduction

Welcome to the coding test! In this exercise, you'll be working on a simplified **Event Sourcing** project. The system
processes events synchronously, meaning that events are applied immediately to the relevant projections without
asynchronous queues. Your tasks involve implementing core functionality for a bank account, handling business rules, and
ensuring the correctness of projections.

We encourage you to write clean, modular, and testable code. Each task builds upon the previous one, so focus on
correctness and maintainability.

---

## Instructions

1. Create a new **PRIVATE** GitHub repository for the assignment.
2. Add your email to the `README.md` file in your repository for reference.
3. Add **`(Collaborator name)`** as a collaborator to the repository.
4. Carefully read and understand the questions and requirements before starting.
5. Record any assumptions you make in a file named `ASSUMPTIONS.md` so that interviewers can review them.
6. This is a **CONFIDENTIAL** assignment and must not be shared with anyone else.

---

## 1. Implementing a Deposit Feature

### Task Description:

Implement the logic for depositing money into a bank account. The deposit should:

- Validate that the amount is positive.
- Update the account balance.
- Emit a `MoneyDeposited` event with the appropriate details.

### Requirements:

- Add a method `depositMoney` to the `BankAccount` class.
- Emit a `MoneyDeposited` event when the deposit is successful.
- Write tests to:
    - Verify that the balance increases correctly.
    - Ensure that invalid deposits (e.g., negative amounts) are rejected.

---

## 2. Handling Overdrafts

### Task Description:

Implement withdrawal logic for the bank account, considering overdraft limits. The withdrawal should:

- Check if the withdrawal amount exceeds the balance and overdraft limit combined.
- Deduct the amount from the balance if valid.
- Emit a `MoneyWithdrawn` event and, if the account goes into overdraft, emit an `OverdraftUsed` event.

### Requirements:

- Add a method `withdrawMoney` to the `BankAccount` class.
- Ensure the method respects overdraft limits.
- Write tests to:
    - Verify withdrawals within the balance.
    - Test overdraft scenarios (valid and exceeding limits).
    - Handle invalid withdrawals (e.g., negative amounts).

---

## 3. Updating the Projection with Projectors

### Task Description:

The `BankAccountProjection` represents the read model for account balances. Your task is to ensure that the corresponding **projectors** correctly handle the following events to update the projection:
- `MoneyDeposited`
- `MoneyWithdrawn`
- `OverdraftUsed`

Each projector should apply the event logic to update the projection accurately in the database.

### Requirements:

- Implement or enhance the projectors responsible for updating the `BankAccountProjection` based on the specified events.
- Ensure that the balance is updated correctly for each event type in the database.
- Write tests tov verify that the projectors apply the events correctly to the projection.

---

## Composer Scripts

This project includes several Composer scripts to simplify common development tasks. You can execute these scripts using
the following commands:

### Code Quality Tools

#### Static Code Analysis with PHPStan

Analyzes the codebase for potential bugs and enforces best practices:

```bash
composer check:static-analysis
```

#### Code Style Check and Fix with PHP CS Fixer

Check code style compliance:

```bash
composer check:code-style
```  

Automatically fix code style issues:

```bash
composer fix:code-style
```

### Tests

#### Unit Tests

Run unit tests with coverage enabled:

```bash
composer test:unit
```

#### Integration Tests

Run integration tests with coverage:

```bash
composer test:integration
```

#### Application Tests

Run application tests with coverage:

```bash
composer test:application
```

#### Run All Tests

Execute all test types (unit, integration, application) sequentially:

```bash
composer test:all
```

### Symfony Auto-Scripts

The following scripts are executed automatically after `composer install` or `composer update`:

- **Clear Cache**
- **Install Assets**

These scripts are configured under the `auto-scripts` section in `composer.json`.

---

## FAQ

### Where do I have to solve the assignment?

You can implement the solution on your local system or use any virtual server on the cloud/VPS. Please do not destroy your work after submission, as it may be needed again during the panel interview.

### Do I have to use a particular operating system?

You can choose the operating system of your preference.

### Can I make the repository public?

No, the repository must remain private.

### Can I delete my local work after pushing it to the repository?

No, you should retain your local work as it may be required later.