<?php

namespace App\Domain\Budget\Manager;

use App\Domain\Budget\Entity\Budget;
use App\Domain\Budget\Model\Search\BudgetSearchCommand;
use App\Domain\Budget\Repository\BudgetRepository;
use App\Domain\Budget\ValueObject\BudgetValueObject;

class BudgetManager
{
    public function __construct(
        private readonly BudgetRepository $budgetRepository
    ) {
    }

    public function create(Budget $budget): void
    {
        $this->budgetRepository
            ->create($budget)
            ->flush();
    }

    public function disable(Budget $budget): void
    {
        $budget->setEnable(false);

        $this->budgetRepository->flush();
    }

    public function update(): void
    {
        $this->budgetRepository->flush();
    }

    /**
     * @return Budget[]
     */
    public function search(?BudgetSearchCommand $command = null): array
    {
        $command ??= new BudgetSearchCommand();

        return $this->budgetRepository
            ->search($command)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return BudgetValueObject[]
     */
    public function searchValueObject(?BudgetSearchCommand $command = null): array
    {
        $command ??= new BudgetSearchCommand();

        return $this->budgetRepository
            ->searchValueObject($command)
            ->getQuery()
            ->getResult();
    }
}
