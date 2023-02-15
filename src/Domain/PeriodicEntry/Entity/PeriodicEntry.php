<?php

namespace App\Domain\PeriodicEntry\Entity;

use App\Domain\Budget\Entity\Budget;
use App\Domain\Entry\Model\EntryTypeEnum;
use App\Domain\PeriodicEntry\Model\PeriodicEntryInterface;
use App\Domain\PeriodicEntry\Repository\PeriodicEntryRepository;
use App\Shared\Model\TimestampableTrait;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\When;

#[ORM\Entity(repositoryClass: PeriodicEntryRepository::class)]
// #[ORM\HasLifecycleCallbacks]
class PeriodicEntry implements PeriodicEntryInterface
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column]
    #[NotBlank]
    private string $name;

    #[ORM\Column(type: Types::FLOAT)]
    #[When(
        expression: 'this.isSpent()',
        constraints: [
            new GreaterThan(0.0),
            new NotBlank(),
        ]
    )]
    private float $amount = 0.0;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[NotBlank]
    private DateTimeImmutable $executionDate;

    #[ORM\ManyToMany(targetEntity: Budget::class, inversedBy: 'periodicEntries', fetch: 'EXTRA_LAZY')]
    private Collection $budgets;

    public function __construct()
    {
        $this->createdAt     = new DateTimeImmutable();
        $this->executionDate = new DateTimeImmutable();
        $this->budgets       = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): PeriodicEntry
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): PeriodicEntry
    {
        $this->name = $name;

        return $this;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): PeriodicEntry
    {
        $this->amount = $amount;

        return $this;
    }

    public function getExecutionDate(): DateTimeImmutable
    {
        return $this->executionDate;
    }

    public function setExecutionDate(DateTimeImmutable $executionDate): PeriodicEntry
    {
        $this->executionDate = $executionDate;

        return $this;
    }

    public function getBudgets(): Collection
    {
        return $this->budgets;
    }

    public function setBudgets(Collection $budgets): PeriodicEntry
    {
        $this->budgets = $budgets;

        return $this;
    }

    public function addBudget(Budget $budget): PeriodicEntry
    {
        if (!$this->budgets->contains($budget)) {
            $this->budgets->add($budget);
        }

        return $this;
    }

    public function removeBudget(Budget $budget): PeriodicEntry
    {
        if ($this->budgets->contains($budget)) {
            $this->budgets->removeElement($budget);
        }

        return $this;
    }

    public function getType(): EntryTypeEnum
    {
        return $this->budgets->isEmpty()
            ? EntryTypeEnum::TYPE_SPENT
            : EntryTypeEnum::TYPE_FORECAST;
    }

    public function isForecast(): bool
    {
        return EntryTypeEnum::TYPE_FORECAST === $this->getType();
    }

    public function isSpent(): bool
    {
        return EntryTypeEnum::TYPE_SPENT === $this->getType();
    }
}
