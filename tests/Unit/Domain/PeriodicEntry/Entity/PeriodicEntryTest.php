<?php

namespace App\Tests\Unit\Domain\PeriodicEntry\Entity;

use App\Domain\Budget\Entity\Budget;
use App\Domain\Entry\Model\EntryTypeEnum;
use App\Domain\PeriodicEntry\Entity\PeriodicEntry;
use PHPUnit\Framework\TestCase;

class PeriodicEntryTest extends TestCase
{
    public function testAPeriodicEntryWithBudgetMustReturnForecastType(): void
    {
        $periodicEntry = new PeriodicEntry();
        $budget        = (new Budget())
            ->setAmount(rand(1, 100));

        $periodicEntry->addBudget($budget);

        self::assertSame(EntryTypeEnum::TYPE_FORECAST, $periodicEntry->getType());
        self::assertTrue($periodicEntry->isForecast());
    }

    public function testAPeriodicEntryWithoutBudgetsMustReturnSpentType(): void
    {
        $periodicEntry = new PeriodicEntry();

        self::assertSame(EntryTypeEnum::TYPE_SPENT, $periodicEntry->getType());
        self::assertTrue($periodicEntry->isSpent());
    }
}
