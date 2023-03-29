<?php

namespace App\Tests\Integration\Shared\Operator;

use App\Domain\Budget\Entity\Budget;
use App\Domain\Entry\Manager\EntryManager;
use App\Shared\Model\Transfer;
use App\Shared\Operator\HomeOperator;
use App\Tests\Factory\BudgetFactory;
use App\Tests\Integration\Shared\KernelTestCase;

class HomeOperatorTest extends KernelTestCase
{
    private const BUDGET_SOURCE_NAME = 'Budget source name';
    private const BUDGET_TARGET_NAME = 'Budget target name';
    private const BUDGET_AMOUNT      = 100.0;
    private HomeOperator $homeOperator;
    private EntryManager $entryManager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->homeOperator = self::getContainer()->get(HomeOperator::class);
        $this->entryManager = self::getContainer()->get(EntryManager::class);
    }

    public function testTransferMustMoveAmountFromSpentToBudgetSuccessfully(): void
    {
        /** @var Budget $budgetTarget */
        $budgetTarget = BudgetFactory::findOrCreate([
            'name'   => self::BUDGET_TARGET_NAME,
            'amount' => 0,
        ])->object();

        $initialBalance = $this->entryManager->balance();
        $this->transfer(null, $budgetTarget);

        $newBalance = $this->entryManager->balance();
        self::assertSame($initialBalance->getTotalSpent() - self::BUDGET_AMOUNT, $newBalance->getTotalSpent());
        self::assertSame($initialBalance->getTotalForecast() + self::BUDGET_AMOUNT, $newBalance->getTotalForecast());
        self::assertSame($initialBalance->getTotal(), $newBalance->getTotal());
    }

    public function testTransferMustMoveAmountBetweenTwoBudgetSuccessfully(): void
    {
        /** @var Budget $budgetSource */
        $budgetSource = BudgetFactory::findOrCreate([
            'name'   => self::BUDGET_SOURCE_NAME,
            'amount' => 0,
        ])->object();

        /** @var Budget $budgetTarget */
        $budgetTarget = BudgetFactory::findOrCreate([
            'name'   => self::BUDGET_TARGET_NAME,
            'amount' => 0,
        ])->object();

        $initialBalance = $this->entryManager->balance();
        $this->transfer($budgetSource, $budgetTarget);

        $newBalance = $this->entryManager->balance();
        self::assertSame($initialBalance->getTotal(), $newBalance->getTotal());
    }

    public function testTransferMustMoveAmountBudgetToSpentSuccessfully(): void
    {
        /** @var Budget $budgetSource */
        $budgetSource = BudgetFactory::findOrCreate([
            'name'   => self::BUDGET_SOURCE_NAME,
            'amount' => 0,
        ])->object();

        $initialBalance = $this->entryManager->balance();
        $this->transfer($budgetSource, null);

        $newBalance = $this->entryManager->balance();
        self::assertSame($initialBalance->getTotalSpent() + self::BUDGET_AMOUNT, $newBalance->getTotalSpent());
        self::assertSame($initialBalance->getTotalForecast() - self::BUDGET_AMOUNT, $newBalance->getTotalForecast());
        self::assertSame($initialBalance->getTotal(), $newBalance->getTotal());
    }

    private function transfer(?Budget $budgetSource, ?Budget $budgetTarget): void
    {
        $transfer = (new Transfer())
            ->setAmount(self::BUDGET_AMOUNT)
            ->setBudgetSource($budgetSource)
            ->setBudgetTarget($budgetTarget);

        $this->homeOperator->transfer($transfer);

        self::assertSame(-self::BUDGET_AMOUNT, $budgetSource?->getProgress() ?? -self::BUDGET_AMOUNT);
        self::assertSame(self::BUDGET_AMOUNT, $budgetTarget?->getProgress() ?? self::BUDGET_AMOUNT);
    }
}
