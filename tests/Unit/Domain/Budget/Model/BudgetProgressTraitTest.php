<?php

namespace App\Tests\Unit\Domain\Budget\Model;

use App\Domain\Budget\Entity\Budget;
use PHPUnit\Framework\TestCase;

class BudgetProgressTraitTest extends TestCase
{
    public function testRelativeProgressMustReturnZeroWithNoAmount(): void
    {
        $budgetProgressTrait = $this->createPartialMock(Budget::class, [
            'getProgress',
            'getAmount',
        ]);

        $budgetProgressTrait
            ->expects(self::any())
            ->method('getProgress')
            ->willReturn(50.0);

        $budgetProgressTrait
            ->expects(self::any())
            ->method('getAmount')
            ->willReturn(0.0);

        self::assertSame(0.0, $budgetProgressTrait->getRelativeProgress());
    }
}
