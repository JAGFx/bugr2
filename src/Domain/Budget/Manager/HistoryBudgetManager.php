<?php

namespace App\Domain\Budget\Manager;

use App\Domain\Budget\Entity\HistoryBudget;
use App\Domain\Budget\Model\Search\BudgetSearchCommand;
use App\Domain\Budget\Repository\HistoryBudgetRepository;

class HistoryBudgetManager
{
    public function __construct(
        private HistoryBudgetRepository $repository
    ) {
    }

    /**
     * @return string[]
     */
    public function getAvailableYears(): array
    {
        /** @var string[] $years */
        $years = $this->repository->getAvailableYear()
            ->getQuery()
            ->getSingleColumnResult();

        return $years;
    }

    /**
     * @return HistoryBudget[]
     */
    public function getHistories(BudgetSearchCommand $command): array
    {
        /** @var HistoryBudget[] $histories */
        $histories = $this->repository
            ->search($command)
            ->getQuery()
            ->getResult();

        return $histories;
    }
}
