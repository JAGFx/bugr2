<?php

namespace App\Domain\PeriodicEntry\ValueObject;

use App\Domain\Entry\Model\EntryTypeEnum;
use App\Domain\PeriodicEntry\Model\PeriodicEntryInterface;
use DateTimeImmutable;

class PeriodicEntryValueObject implements PeriodicEntryInterface
{
    public function __construct(
        private readonly ?int $id,
        private readonly string $name,
        private readonly float $amount,
        private readonly DateTimeImmutable $executionDate,
        private readonly bool $isForecast,
        private readonly ?int $countBudgets
    ) {
    }

    public function getId(): ?int
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

    public function getExecutionDate(): DateTimeImmutable
    {
        return $this->executionDate;
    }

    public function isForecast(): bool
    {
        return $this->isForecast;
    }

    public function getType(): EntryTypeEnum
    {
        return $this->isForecast
            ? EntryTypeEnum::TYPE_FORECAST
            : EntryTypeEnum::TYPE_SPENT;
    }

    public function isSpent(): bool
    {
        return !$this->isForecast;
    }

    public function countBudgets(): ?int
    {
        return $this->countBudgets;
    }
}
