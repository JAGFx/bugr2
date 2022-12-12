<?php

namespace App\Domain\Budget\Model\Search;

class BudgetSearchCommand
{
    public function __construct(
        private ?\DateTimeImmutable $from = null,
        private ?\DateTimeImmutable $to = null,
    ) {
    }

    public function getFrom(): ?\DateTimeImmutable
    {
        return $this->from;
    }

    public function setFrom(?\DateTimeImmutable $from): self
    {
        $this->from = $from;

        return $this;
    }

    public function getTo(): ?\DateTimeImmutable
    {
        return $this->to;
    }

    public function setTo(?\DateTimeImmutable $to): self
    {
        $this->to = $to;

        return $this;
    }
}
