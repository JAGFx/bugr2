<?php

namespace App\Tests\Integration\Domain\Budget\Repository;

use App\Domain\Budget\Manager\BudgetManager;
use App\Domain\Budget\Model\Search\BudgetSearchCommand;
use App\Domain\Budget\ValueObject\BudgetValueObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BudgetRepositoryTest extends KernelTestCase
{
    private BudgetManager $budgetManager;

    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $this->budgetManager = $container->get(BudgetManager::class);
    }

    /**
     * @return BudgetValueObject[]
     */
    private function getBudgetVos(int $expectCount, int $year, ?bool $showCredit = null): array {
        $command = (new BudgetSearchCommand())
            ->setYear($year)
            ->setShowCredits($showCredit);
        $budgetsVos =  $this->budgetManager->searchValueObject($command);

        self::assertCount($expectCount, $budgetsVos);

        return $budgetsVos;
    }

    public function testBudgetSpentProgress(): void {
        $budgetsVos = $this->getBudgetVos(1, 2016, false);
        $budgetsVo = reset($budgetsVos);

        self::assertInstanceOf( BudgetValueObject::class, $budgetsVo );
        self::assertSame('Budget spent 2016', $budgetsVo->getName());
        self::assertSame(256.0, $budgetsVo->getAmount());
        self::assertSame(192.0, $budgetsVo->getProgress());
    }

    public function testBudgetForecastProgress(): void {
        $budgetsVos = $this->getBudgetVos(1, 2016, true);

        $budgetsVo = reset($budgetsVos);

        self::assertInstanceOf( BudgetValueObject::class, $budgetsVo );
        self::assertSame('Budget spent 2016', $budgetsVo->getName());
        self::assertSame(256.0, $budgetsVo->getAmount());
        self::assertSame(128.0, $budgetsVo->getProgress());
    }

    // TODO Add test for global (spent + forecast)
}