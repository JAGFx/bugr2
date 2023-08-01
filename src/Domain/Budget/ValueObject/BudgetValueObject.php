<?php

namespace App\Domain\Budget\ValueObject;

use App\Domain\Budget\Model\BudgetProgressTrait;

final readonly class BudgetValueObject
{
    use BudgetProgressTrait;

    public function __construct(
        private int $id,
        private string $name,
        private float $amount,
        private bool $enable,
        private float $progress,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function isEnable(): bool
    {
        return $this->enable;
    }

    public function getProgress(bool $showAsSpentOnly = false): float
    {
        return ($showAsSpentOnly)
            ? abs($this->progress)
            : $this->progress;
    }
}
