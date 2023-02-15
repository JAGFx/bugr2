<?php

namespace App\Domain\PeriodicEntry\Model;

use App\Domain\Entry\Model\EntryTypeEnum;
use DateTimeImmutable;

interface PeriodicEntryInterface
{
    public function getId(): ?int;

    public function getName(): string;

    public function getAmount(): float;

    public function getExecutionDate(): DateTimeImmutable;

    public function isForecast(): bool;

    public function isSpent(): bool;

    public function getType(): EntryTypeEnum;
}
