<?php

namespace App\Domain\Budget\Entity;

use App\Domain\Budget\Model\BudgetStatusEnum;
use App\Domain\Budget\Repository\HistoryBudgetRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;

#[Entity(repositoryClass: HistoryBudgetRepository::class)]
class HistoryBudget
{
    #[Id]
    #[Column]
    #[GeneratedValue]
    private ?int $id = null;

    #[Column(type: Types::DATE_IMMUTABLE)]
    private ?DateTimeImmutable $date = null;

    #[Column]
    private ?float $relativeProgress = null;

    #[Column]
    private ?float $amount = null;

    #[ManyToOne(targetEntity: Budget::class)]
    private ?Budget $budget = null;

    public function getStatus(): BudgetStatusEnum
    {
        return BudgetStatusEnum::statusByProgress($this->relativeProgress ?? 0.0);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(?DateTimeImmutable $date): HistoryBudget
    {
        $this->date = $date;

        return $this;
    }

    public function getRelativeProgress(): ?float
    {
        return $this->relativeProgress;
    }

    public function setRelativeProgress(?float $relativeProgress): HistoryBudget
    {
        $this->relativeProgress = $relativeProgress;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(?float $amount): HistoryBudget
    {
        $this->amount = $amount;

        return $this;
    }

    public function getBudget(): ?Budget
    {
        return $this->budget;
    }

    public function setBudget(?Budget $budget): HistoryBudget
    {
        $this->budget = $budget;

        return $this;
    }
}
