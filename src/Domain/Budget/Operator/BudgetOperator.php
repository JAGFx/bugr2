<?php

namespace App\Domain\Budget\Operator;

use App\Domain\Account\Manager\AccountManager;
use App\Domain\Budget\Entity\Budget;
use App\Domain\Budget\ValueObject\BudgetCashFlowByAccountValueObject;

class BudgetOperator
{
    public function __construct(
        private readonly AccountManager $accountManager,
    ) {
    }

    /**
     * @return BudgetCashFlowByAccountValueObject[]
     */
    public function getBudgetCashFlowsByAccount(Budget $budget): array
    {
        $accounts = $this->accountManager->getAccounts();

        $cashFlows = [];
        foreach ($accounts as $account) {
            $cashFlow = $budget->getCashFlow($account);

            if (0.0 === $cashFlow) {
                continue;
            }

            $cashFlows[] = new BudgetCashFlowByAccountValueObject(
                $budget,
                $account,
                $cashFlow
            );
        }

        return $cashFlows;
    }
}
