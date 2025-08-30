<?php

namespace App\Domain\Account\Manager;

use App\Domain\Account\Entity\Account;
use App\Domain\Account\Repository\AccountRepository;
use App\Domain\Assignment\Entity\Assignment;

class AccountManager
{
    public function __construct(
        private readonly AccountRepository $repository,
    ) {
    }

    public function getBalanceAssignments(Account $account): float
    {
        return array_sum(array_map(fn (Assignment $assignment): float => $assignment->getAmount(), $account->getAssignments()->toArray()));
    }

    /**
     * @return Account[]
     */
    public function getAccounts(): array
    {
        /** @var Account[] $accounts */
        $accounts = $this->repository->findAll();

        return $accounts;
    }

    public function toggle(Account $account): void
    {
        $account->setEnable(!$account->isEnable());

        $this->update();
    }

    public function create(Account $account): void
    {
        $this->repository
            ->create($account)
            ->flush();
    }

    public function update(): void
    {
        $this->repository->flush();
    }
}
