<?php

namespace App\Domain\Entry\Entity;

use App\Domain\Budget\Entity\Budget;
use App\Domain\Entry\Model\EntryTypeEnum;
use App\Domain\Entry\Repository\EntryRepository;
use App\Shared\Model\TimstampableTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EntryRepository::class)]
class Entry
{
    use TimstampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column]
    private string $name;

    #[ORM\Column(type: 'float')]
    private float $amount = 0;

    #[ORM\Column(enumType: EntryTypeEnum::class)]
    private EntryTypeEnum $type = EntryTypeEnum::TYPE_SPENT;

    #[ORM\ManyToOne(fetch: 'EXTRA_LAZY', inversedBy: 'entries')]
    private ?Budget $budget = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getType(): EntryTypeEnum
    {
        return $this->type;
    }

    public function setType(EntryTypeEnum $type): self
    {
        $this->type = $type;

        return $this;
    }

    // ----

    public function isForecast(): bool
    {
        return EntryTypeEnum::TYPE_FORECAST === $this->type;
    }

    public function isSpent(): bool
    {
        return EntryTypeEnum::TYPE_SPENT === $this->type;
    }

    public function getBudget(): ?Budget
    {
        return $this->budget;
    }

    public function setBudget(?Budget $budget): self
    {
        $this->budget = $budget;

        return $this;
    }
}
