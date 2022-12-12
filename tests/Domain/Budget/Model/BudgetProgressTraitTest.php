<?php

namespace App\Tests\Domain\Budget\Model;

use App\Domain\Budget\Model\BudgetProgressTrait;
use Generator;
use PHPUnit\Framework\TestCase;

class BudgetProgressTraitTest extends TestCase
{
    private function getBudgetProgressTraitData(): Generator
    {
        yield '50% progression' => [50, 100];
        yield '100% progression' => [100, 100];
        yield 'Over progression' => [150, 100];
        yield 'No progression' => [0, 100];
        yield 'Under progression' => [-50, 100];
        yield 'No amount' => [50, 0];
    }

    /** @dataProvider getBudgetProgressTraitData */
    public function testRelativeProgressNeverBeUnderZero(float $progress, float $amount): void
    {
        $budgetProgressTrait = $this->getMockForTrait(BudgetProgressTrait::class, mockedMethods: [
            'getProgress',
            'getAmount'
        ]);

        $budgetProgressTrait
            ->expects(self::any())
            ->method('getProgress')
            ->willReturn($progress);

        $budgetProgressTrait
            ->expects(self::any())
            ->method('getAmount')
            ->willReturn($amount);

        self::assertGreaterThanOrEqual(0, $budgetProgressTrait->getRelativeProgress());
        self::assertLessThanOrEqual(100, $budgetProgressTrait->getRelativeProgress());
    }
}