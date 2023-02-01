<?php

namespace App\Shared\Model;

use Doctrine\ORM\Mapping\Column;
use Gedmo\Mapping\Annotation\Timestampable;

trait TimestampableTrait
{
    #[Column(type: 'datetime_immutable')]
    #[Timestampable(on: 'create')]
    private \DateTimeImmutable $createdAt;
    #[Column(type: 'datetime_immutable')]
    #[Timestampable(on: 'update')]
    private \DateTimeImmutable $updatedAt;

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
