<?php

namespace App\Domain\Budget\Model;

trait BudgetProgressTrait
{
    abstract public function getProgress(): float;

    abstract public function getAmount(): float;

    public function getRelativeProgress(): float
    {
        if (0.0 === $this->getAmount()) {
            return 0.0;
        }

        return $this->getProgress() * 100 / $this->getAmount();
    }

    public function getStatus(): BudgetStatusEnum
    {
        $relativeProgress = $this->getRelativeProgress();

        return BudgetStatusEnum::statusByProgress($relativeProgress);
    }
}
