<?php

namespace App\Tests\Integration\Domain\PeiodicEntry\Repository;

use App\Domain\Entry\Model\EntryTypeEnum;
use App\Domain\PeriodicEntry\Form\PeriodicEntrySearchCommand;
use App\Domain\PeriodicEntry\Manager\PeriodicEntryManager;
use App\Domain\PeriodicEntry\ValueObject\PeriodicEntryValueObject;
use App\Tests\Factory\BudgetFactory;
use App\Tests\Factory\PeriodicEntryFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class PeriodicEntryRepositoryTest extends KernelTestCase
{
    use ResetDatabase;
    use Factories;
    private PeriodicEntryManager $periodicEntryManager;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $this->periodicEntryManager = $container->get(PeriodicEntryManager::class);

        $this->populateDatabase();
    }

    private function populateDatabase(): void
    {
        PeriodicEntryFactory::createSequence([
            [
                'amount' => 42,
                'name'   => 'Spent',
            ],
            [
                'amount'  => 42,
                'name'    => 'Forecast',
                'budgets' => BudgetFactory::createSequence([
                    [
                        'amount' => 123,
                    ],
                    [
                        'amount' => 456,
                    ],
                ]),
            ],
        ]);
    }

    public function testGetPeriodicEntriesVosIsRightClass(): void
    {
        $periodicEntries = $this->periodicEntryManager->searchValueObject();

        foreach ($periodicEntries as $periodicEntry) {
            self::assertInstanceOf(PeriodicEntryValueObject::class, $periodicEntry);
        }
    }

    public function testPeriodicEntriesSpentIsRight(): void
    {
        $periodicEntriesSpent = $this->periodicEntryManager->searchValueObject(
            new PeriodicEntrySearchCommand(EntryTypeEnum::TYPE_SPENT)
        );

        self::assertCount(1, $periodicEntriesSpent);

        /** @var PeriodicEntryValueObject $periodicEntrySpent */
        $periodicEntrySpent = reset($periodicEntriesSpent);
        self::assertSame('Spent', $periodicEntrySpent->getName());
        self::assertSame(42.0, $periodicEntrySpent->getAmount());
        self::assertSame(EntryTypeEnum::TYPE_SPENT, $periodicEntrySpent->getType());
        self::assertTrue($periodicEntrySpent->isSpent());
    }

    public function testPeriodicEntriesForecastIsRight(): void
    {
        $periodicEntriesSpent = $this->periodicEntryManager->searchValueObject(
            new PeriodicEntrySearchCommand(EntryTypeEnum::TYPE_FORECAST)
        );

        self::assertCount(1, $periodicEntriesSpent);

        /** @var PeriodicEntryValueObject $periodicEntrySpent */
        $periodicEntrySpent = reset($periodicEntriesSpent);
        self::assertSame('Forecast', $periodicEntrySpent->getName());
        self::assertSame(48.25, $periodicEntrySpent->getAmount());
        self::assertSame(EntryTypeEnum::TYPE_FORECAST, $periodicEntrySpent->getType());
        self::assertTrue($periodicEntrySpent->isForecast());
    }
}
