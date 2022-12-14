<?php

namespace App\Domain\Budget\Model\Search;

class BudgetSearchCommand
{
    public function __construct(
        private ?int $year = null,
        private ?bool $showCredits = null
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
}
