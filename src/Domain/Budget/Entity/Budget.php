<?php

namespace App\Domain\Budget\Entity;

use App\Domain\Budget\Model\BudgetProgressTrait;
use App\Domain\Budget\Repository\BudgetRepository;
use App\Domain\Entry\Entity\Entry;
use App\Domain\PeriodicEntry\Entity\PeriodicEntry;
use App\Shared\Model\TimstampableTrait;
use App\Shared\Utils\YearRange;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\GreaterThan;

#[ORM\Entity(repositoryClass: BudgetRepository::class)]
class Budget
{
    use BudgetProgressTrait;
    use TimstampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column]
    private string $name;

    #[ORM\Column(type: 'float')]
    #[GreaterThan(value: 0)]
    private float $amount;

    #[ORM\Column(type: 'simple_array', nullable: true)]
    private array $historic = [];

    #[ORM\ManyToMany(targetEntity: PeriodicEntry::class, mappedBy: 'budgets', fetch: 'EXTRA_LAZY')]
    private Collection $periodicEntries;

    #[ORM\OneToMany(mappedBy: 'budget', targetEntity: Entry::class, cascade: ['persist', 'remove'], fetch: 'EXTRA_LAZY', indexBy: 'createdAt')]
    private Collection $entries;

    #[ORM\Column(type: 'boolean')]
    private bool $enable = true;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->periodicEntries = new ArrayCollection();
        $this->entries = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = round($amount, 2);

        return $this;
    }

    public function getHistoric(): ?array
    {
        return $this->historic;
    }

    /**
     * @return Collection<PeriodicEntry>
     */
    public function getPeriodicEntries(): Collection
    {
        return $this->periodicEntries;
    }

    public function addPeriodicEntry(PeriodicEntry $periodicEntry): self
    {
        if (!$this->periodicEntries->contains($periodicEntry)) {
            $this->periodicEntries[] = $periodicEntry;
            $periodicEntry->addBudget($this);
        }

        return $this;
    }

    public function removePeriodicEntry(PeriodicEntry $periodicEntry): self
    {
        if ($this->periodicEntries->contains($periodicEntry)) {
            $this->periodicEntries->removeElement($periodicEntry);
            $periodicEntry->removeBudget($this);
        }

        return $this;
    }

    /**
     * @return Collection<Entry>
     */
    public function getEntries(): Collection
    {
        return $this->entries;
    }

    public function setEntries($entries): self
    {
        $this->entries = $entries;

        return $this;
    }

    public function addEntry(Entry $entry): self
    {
        if (!$this->entries->contains($entry)) {
            $this->entries->add($entry);
        }

        return $this;
    }

    public function getEnable(): bool
    {
        return $this->enable;
    }

    public function setEnable(bool $enable): self
    {
        $this->enable = $enable;

        return $this;
    }

    public function getProgress(): float
    {
        return array_reduce(
            $this->entries->toArray(),
            fn (float $currentSum, Entry $entry): float => $currentSum + $entry->getAmount(),
            0
        );
    }

    public function getCashFlow(): float
    {
        $entriesForLastYear = $this->entries
            ->filter(fn (Entry $entry): bool => $entry->getCreatedAt() < YearRange::fisrtDayOf(YearRange::current()) || $entry->isABalancing());

        $cashFlow = array_reduce(
            $entriesForLastYear->toArray(),
            fn (float $cashFlow, Entry $entry): float => $cashFlow + $entry->getAmount(),
            0.0
        );

        return (0.0 === $cashFlow)
            ? 0.0
            : $cashFlow - $this->amount;
    }

    public function hasNegativeCashFlow(): bool
    {
        return round($this->getCashFlow(), 2) < 0.0;
    }

    public function hasPositiveCashFlow(): bool
    {
        return round($this->getCashFlow(), 2) > 0.0;
    }
}
