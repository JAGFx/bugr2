<?php

namespace App\Domain\Entry\Entity;

use App\Domain\Budget\Entity\Budget;
use App\Domain\Entry\Model\EntryKindEnum;
use App\Domain\Entry\Model\EntryTypeEnum;
use App\Domain\Entry\Repository\EntryRepository;
use App\Shared\Model\TimestampableTrait;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotEqualTo;

#[Entity(repositoryClass: EntryRepository::class)]
class Entry
{
    use TimestampableTrait;

    #[Id]
    #[GeneratedValue]
    #[Column(type: 'integer')]
    private int $id;

    #[Column]
    #[NotBlank]
    private string $name;

    #[Column(type: 'float')]
    #[NotBlank]
    #[NotEqualTo(0)]
    private float $amount = 0;

    #[ManyToOne(fetch: 'EXTRA_LAZY', inversedBy: 'entries')]
    #[NotBlank(allowNull: true)]
    private ?Budget $budget = null;

    #[Column(enumType: EntryKindEnum::class, options: ['default' => EntryKindEnum::DEFAULT])]
    #[NotBlank]
    private EntryKindEnum $kind = EntryKindEnum::DEFAULT;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

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
        return (null === $this->budget)
            ? EntryTypeEnum::TYPE_SPENT
            : EntryTypeEnum::TYPE_FORECAST;
    }

    // ----

    public function isForecast(): bool
    {
        return EntryTypeEnum::TYPE_FORECAST === $this->getType();
    }

    public function isSpent(): bool
    {
        return EntryTypeEnum::TYPE_SPENT === $this->getType();
    }

    public function getBudget(): ?Budget
    {
        return $this->budget;
    }

    public function setBudget(?Budget $budget): self
    {
        $this->budget = $budget;

        if ($budget instanceof Budget) {
            $budget->addEntry($this);
        }

        return $this;
    }

    public function getKind(): EntryKindEnum
    {
        return $this->kind;
    }

    public function setKind(EntryKindEnum $kind): self
    {
        $this->kind = $kind;

        return $this;
    }

    public function isABalancing(): bool
    {
        return EntryKindEnum::BALANCING === $this->kind;
    }
}
