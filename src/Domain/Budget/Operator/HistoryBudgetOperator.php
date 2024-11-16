<?php

namespace App\Domain\Budget\Operator;

use App\Domain\Budget\Entity\HistoryBudget;
use App\Domain\Budget\Manager\BudgetManager;
use App\Domain\Budget\Manager\HistoryBudgetManager;
use App\Domain\Budget\Model\Search\BudgetSearchCommand;
use App\Domain\Budget\Model\Search\HistoryBudgetSearchCommand;
use App\Shared\Utils\YearRange;

class HistoryBudgetOperator
{
    public function __construct(
        private readonly BudgetManager $budgetManager,
        private readonly HistoryBudgetManager $historyBudgetManager,
    ) {
    }

    public function generateHistoryBudgetsForYear(int $year): void
    {
        $budgetsValues = $this->budgetManager->searchValueObject(
            (new BudgetSearchCommand())
               ->setShowCredits(false)
               ->setYear($year)
        );

        foreach ($budgetsValues as $budgetValue) {
            $budget = $this->budgetManager->find($budgetValue->getId());

            if (is_null($budget)) {
                continue;
            }

            $historyBudgets = $this->historyBudgetManager->getHistories(
                new HistoryBudgetSearchCommand(
                    $budget,
                    $year
                )
            );

            if ([] !== $historyBudgets) {
                continue;
            }

            $historyBudget = (new HistoryBudget())
                ->setBudget($budget)
                ->setAmount($budget->getAmount())
                ->setDate(YearRange::firstDayOf($year))
                ->setRelativeProgress($budgetValue->getProgress(true))
            ;

            $this->historyBudgetManager->create($historyBudget);
        }
    }
}
