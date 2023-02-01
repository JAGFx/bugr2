<?php

namespace App\Tests\Integration\Domain\Budget\Manager;

use App\Domain\Budget\Entity\Budget;
use App\Domain\Budget\Manager\BudgetManager;
use App\Domain\Budget\Model\Search\BudgetSearchCommand;
use App\Domain\Entry\Manager\EntryManager;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BudgetManagerTest extends KernelTestCase
{
    private BudgetManager $budgetManager;
    private EntryManager $entryManager;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $this->budgetManager = $container->get(BudgetManager::class);
        $this->entryManager  = $container->get(EntryManager::class);
    }

    private function getBudget(array $data = []): Budget
    {
        $command = new BudgetSearchCommand();
        $command->setName($data['name'] ?? null);

        $result = $this->budgetManager->search($command);

        self::assertCount(1, $result);

        return reset($result);
    }

    public function testBudgetWithPositiveCashFlowMustTransferToSpent(): void
    {
        $initialBalance = $this->entryManager->balance();
        $overflow       = 200;

        $budget = $this->getBudget([
            'name' => 'Budget balance',
        ]);
        $this->budgetManager->balancing($budget);
        $newBalance = $this->entryManager->balance();

        self::assertSame($initialBalance->getTotalSpent() + $overflow, $newBalance->getTotalSpent());
        self::assertSame($initialBalance->getTotalForecast() - $overflow, $newBalance->getTotalForecast());
        self::assertSame($initialBalance->getTotal(), $newBalance->getTotal());
    }
}
