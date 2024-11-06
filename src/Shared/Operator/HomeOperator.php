<?php

namespace App\Shared\Operator;

use App\Domain\Account\Entity\Account;
use App\Domain\Entry\Entity\Entry;
use App\Domain\Entry\Manager\EntryManager;
use App\Domain\Entry\Model\EntryKindEnum;
use App\Shared\Model\Transfer;

class HomeOperator
{
    public function __construct(
        private readonly EntryManager $entryManager
    ) {
    }

    public function transfer(Transfer $transfer, Account $account): void
    {
        $entrySourceName = $transfer->getBudgetSource()?->getName() ?? 'Dépense';
        $entryTargetName = $transfer->getBudgetTarget()?->getName() ?? 'Dépense';

        $entrySource = (new Entry())
            ->setKind(EntryKindEnum::BALANCING)
            ->setBudget($transfer->getBudgetSource())
            ->setAmount(-$transfer->getAmount())
            ->setAccount($account)
            ->setName(sprintf('Transfer depuis %s', $entrySourceName));

        $entryTarget = (new Entry())
            ->setKind(EntryKindEnum::BALANCING)
            ->setBudget($transfer->getBudgetTarget())
            ->setAmount($transfer->getAmount())
            ->setAccount($account)
            ->setName(sprintf('Transfer vers %s', $entryTargetName));

        $transfer
            ->getBudgetSource()
            ?->addEntry($entrySource);

        $transfer
            ->getBudgetTarget()
            ?->addEntry($entryTarget);

        $this->entryManager->create($entrySource);
        $this->entryManager->create($entryTarget);
    }
}
