<?php

namespace App\Tests\Unit\Domain\Budget\Manager;

use App\Domain\Budget\Manager\BudgetManager;
use App\Domain\Budget\Repository\BudgetRepository;
use App\Domain\Entry\Entity\Entry;
use App\Domain\Entry\Manager\EntryManager;
use App\Domain\Entry\Model\EntryKindEnum;
use App\Tests\Unit\Shared\BudgetTestTrait;
use DateTimeImmutable;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class BudgetManagerTest extends TestCase
{
    use BudgetTestTrait;

    private const BUDGET_AMOUNT = 1000.0;

    private BudgetRepository $budgetRepository;
    private EntryManager $entryManager;

    protected function setUp(): void
    {
        $this->budgetRepository = $this->createMock(BudgetRepository::class);
        $this->entryManager = $this->createMock(EntryManager::class);
    }

    public function createBudgetManagerMock(): BudgetManager|MockObject {
        return $this->getMockBuilder(BudgetManager::class)
            ->onlyMethods(['update'])
            ->setConstructorArgs([
                $this->budgetRepository,
                $this->entryManager
            ])
            ->getMock();
    }

    public function testBudgetWithNegativeCashFlowDoNothing(): void {
        $progress = -500.0;
        $budget = $this->generateBudget([
             'amount' => self::BUDGET_AMOUNT,
            'entries' => [
                [
                    'entryName' => 'Past year entry',
                    'entryAmount' => $progress,
                    'entryCreatedAt' => new DateTimeImmutable('-1 year'),
                ]
            ]
        ]);

        $budgetManager = $this->createBudgetManagerMock();
        $budgetManager
            ->expects(self::never())
            ->method('update');

        $budgetManager->balancing($budget);

        self::assertCount(1, $budget->getEntries());
        self::assertSame($progress, $budget->getProgress());
    }

    public function testBudgetWithPositiveCashFlowMustTransferToSpent(): void {
        $overflow = 500.0;
        $budget = $this->generateBudget([
            'amount' => self::BUDGET_AMOUNT,
            'entries' => [
                [
                    'entryName' => 'Past year entry',
                    'entryAmount' => self::BUDGET_AMOUNT + $overflow,
                    'entryCreatedAt' => new DateTimeImmutable('-1 year'),
                ],
                [
                    'entryAmount' => 200
                ]
            ]
        ]);

        $budgetManager = $this->createBudgetManagerMock();
        $budgetManager
            ->expects(self::once())
            ->method('update');

        self::assertSame($overflow, $budget->getCashFlow());

        $budgetManager->balancing($budget);

        $balancingEntry = $budget->getEntries()
            ->filter(fn(Entry $entry) : bool => str_starts_with($entry->getName(), 'Équilibrage'))
            ->first();

        self::assertCount(2 + 1, $budget->getEntries());
        self::assertInstanceOf(Entry::class,$balancingEntry);
        self::assertSame($balancingEntry->getKind(),EntryKindEnum::BALANCING);
        self::assertSame(-$overflow, $balancingEntry->getAmount());
        self::assertSame(0.0, $budget->getCashFlow());
    }
}