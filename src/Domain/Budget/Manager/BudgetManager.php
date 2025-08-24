<?php

namespace App\Domain\Budget\Manager;

use App\Domain\Account\Entity\Account;
use App\Domain\Budget\Entity\Budget;
use App\Domain\Budget\Model\Search\BudgetSearchCommand;
use App\Domain\Budget\Repository\BudgetRepository;
use App\Domain\Budget\ValueObject\BudgetValueObject;
use App\Domain\Entry\Entity\Entry;
use App\Domain\Entry\Manager\EntryManager;
use App\Domain\Entry\Model\EntryKindEnum;

class BudgetManager
{
    public function __construct(
        private readonly BudgetRepository $budgetRepository,
        private readonly EntryManager $entryManager,
    ) {
    }

    public function create(Budget $budget): void
    {
        $this->budgetRepository
            ->create($budget)
            ->flush();
    }

    public function toggle(Budget $budget): void
    {
        $budget->setEnable(!$budget->getEnable());

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

        /** @var Budget[] $result */
        $result = $this->budgetRepository
            ->search($command)
            ->getQuery()
            ->getResult();

        return $result;
    }

    /**
     * @return BudgetValueObject[]
     */
    public function searchValueObject(?BudgetSearchCommand $command = null): array
    {
        $command ??= new BudgetSearchCommand();

        /** @var BudgetValueObject[] $result */
        $result = $this->budgetRepository
            ->searchValueObject($command)
            ->getQuery()
            ->getResult();

        return $result;
    }

    public function find(int $id): ?Budget
    {
        /** @var ?Budget $budget */
        $budget = $this->budgetRepository->find($id);

        return $budget;
    }

    public function balancing(Budget $budget, Account $account): void
    {
        if ($budget->hasPositiveCashFlow() || $budget->hasNegativeCashFlow()) {
            $entryBalanceSpent = new Entry()
                ->setAccount($account)
                ->setName(sprintf('Équilibrage de %s', $budget->getName()))
                ->setKind(EntryKindEnum::BALANCING)
                ->setAmount($budget->getCashFlow());

            $entryBalanceForecast = new Entry()
                ->setAccount($account)
                ->setBudget($budget)
                ->setName(sprintf('Équilibrage de %s', $budget->getName()))
                ->setKind(EntryKindEnum::BALANCING)
                ->setAmount(-$budget->getCashFlow());

            $budget->addEntry($entryBalanceForecast);

            $this->entryManager->create($entryBalanceSpent);
            $this->update();
        }
    }
}
