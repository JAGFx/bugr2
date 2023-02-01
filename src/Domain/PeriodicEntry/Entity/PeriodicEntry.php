<?php

namespace App\Domain\PeriodicEntry\Entity;

use App\Domain\Budget\Entity\Budget;
use App\Domain\Entry\Model\EntryTypeEnum;
use App\Domain\PeriodicEntry\Repository\PeriodicEntryRepository;
use App\Shared\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PeriodicEntryRepository::class)]
#[ORM\HasLifecycleCallbacks]
class PeriodicEntry
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column]
    private string $name;

    #[ORM\Column(type: 'dateinterval')]
    private ?\DateInterval $period = null;

    #[ORM\Column(enumType: EntryTypeEnum::class)]
    private EntryTypeEnum $type = EntryTypeEnum::TYPE_SPENT;

    #[ORM\Column(type: 'float')]
    private float $amount = 0.0;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $executionDate;

    #[ORM\Column(type: 'simple_array', nullable: true)]
    private array $historic = [];

    #[ORM\ManyToMany(targetEntity: Budget::class, inversedBy: 'periodicEntries', fetch: 'EXTRA_LAZY', indexBy: 'shortcut')]
    private Collection $budgets;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->executionDate = new \DateTimeImmutable();
        $this->budgets = new ArrayCollection();
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

    public function getPeriod(): ?\DateInterval
    {
        return $this->period;
    }

    public function setPeriod(?\DateInterval $period): self
    {
        if (!$this->haveNoBudget()) {
            $period = new \DateInterval('P1M');
        }

        $this->period = $period;

        return $this;
    }

    public function getType(): EntryTypeEnum
    {
        return $this->type;
    }

    public function setType(EntryTypeEnum $type): self
    {
        if (!$this->haveNoBudget()) {
            $type = EntryTypeEnum::TYPE_FORECAST;
        }

        $this->type = $type;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->updateAmount();

        $this->amount = round($amount, 2);

        return $this;
    }

    public function getExecutionDate(): \DateTimeImmutable
    {
        return $this->executionDate;
    }

    public function setExecutionDate(\DateTimeImmutable $executionDate): void
    {
        $executionDate->setTime(2, 0);
        $this->executionDate = $executionDate;
    }

    public function getHistoric(): ?array
    {
        return $this->historic;
    }

    /**
     * @return Collection|Budget[]
     */
    public function getBudgets(): Collection
    {
        return $this->budgets;
    }

    public function addBudget(Budget $budget): self
    {
        $this->budgets->set($budget->getShortcut(), $budget);
        $this->updateAmount();

        return $this;
    }

    public function removeBudget(Budget $budget): self
    {
        if ($this->budgets->containsKey($budget->getShortcut())) {
            $this->budgets->remove($budget->getShortcut());
            $this->updateAmount();
        }

        return $this;
    }

    public function haveNoBudget(): bool
    {
        return $this->budgets->isEmpty();
    }

    // ----

    #[ORM\PreUpdate]
    public function onUpdate(): void
    {
        if (empty($this->historic)
            || (!empty($this->historic) && $this->getAmount() !== end($this->historic)['amount'])) {
            $this->historic[] = [
                'date' => new \DateTimeImmutable(),
                'amount' => $this->getAmount(),
            ];
        }
    }

    #[ORM\PreUpdate]
    public function updateAmount(): void
    {
        if (!$this->haveNoBudget()) {
            $amount = 0.0;
            /** @var Budget $budget */
            foreach ($this->budgets as $budget) {
                if ($budget->getEnable()) {
                    $amount += $budget->getAmount();
                }
            }

            $this->amount = round($amount / 12, 2);
        }
    }
}
