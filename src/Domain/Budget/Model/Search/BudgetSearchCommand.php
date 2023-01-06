<?php

namespace App\Domain\Budget\Model\Search;

class BudgetSearchCommand
{
    public function __construct(
        private ?int $year = null,
        private ?bool $showCredits = null,
        private ?string $name = null,
        private ?bool $excludeCurrentYear = null,
        private ?int $budgetId = null,
        private ?bool $enabled = true
    ) {
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(?int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getShowCredits(): ?bool
    {
        return $this->showCredits;
    }

    public function setShowCredits(?bool $showCredits): self
    {
        $this->showCredits = $showCredits;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getExcludeCurrentYear(): ?bool
    {
        return $this->excludeCurrentYear;
    }

    public function setExcludeCurrentYear(?bool $excludeCurrentYear): self
    {
        $this->excludeCurrentYear = $excludeCurrentYear;

        return $this;
    }

    public function getBudgetId(): ?int
    {
        return $this->budgetId;
    }

    public function setBudgetId(?int $budgetId): self
    {
        $this->budgetId = $budgetId;

        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(?bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }
}
