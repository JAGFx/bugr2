<?php

    namespace App\Domain\Budget\Entity;

    use App\Domain\Budget\Model\BudgetProgressTrait;
    use App\Domain\Budget\Repository\BudgetRepository;
    use App\Domain\Entry\Entity\Entry;
    use App\Domain\PeriodicEntry\Entity\PeriodicEntry;
    use App\Shared\Model\TimstampableTrait;
    use DateTimeImmutable;
    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\Common\Collections\Collection;
    use Doctrine\ORM\Mapping as ORM;

    #[ORM\Entity(repositoryClass: BudgetRepository::class)]
    class Budget
    {
        use TimstampableTrait;
        use BudgetProgressTrait;
        
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column(type:'integer')]
        private int $id;

        #[ORM\Column]
        private string $name;

        #[ORM\Column(type: 'float')]
        private float $amount;

        #[ORM\Column(type:'simple_array', nullable: true)]
        private array $historic = [];

        #[ORM\ManyToMany(targetEntity: PeriodicEntry::class, mappedBy: 'budgets', fetch: 'EXTRA_LAZY')]
        private Collection $periodicEntries;

        #[ORM\OneToMany(mappedBy: 'budget', targetEntity: Entry::class, fetch: 'EXTRA_LAZY', indexBy: 'createdAt')]
        private Collection $entries;

        #[ORM\Column(type: 'boolean')]
        private bool $enable = true;

        /**
         * Budget constructor.
         */
        public function __construct()
        {
            $this->createdAt       = new DateTimeImmutable();
            $this->periodicEntries = new ArrayCollection();
            $this->entries         = new ArrayCollection();
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
         * @return Collection|PeriodicEntry[]
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
         * @return Collection|Entry[]
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

        /*public function getSumAmountOfEntries(): float
        {
            $amount      = 0.0;
            $anivDate    = ($this->isAnniversaryYearCalendar())
                ? new DateTime(self::ANNIV_CALENDAR_DATE)
                : new DateTime(self::ANNIV_ACCOUNT_DATE);
            $currentYear = ( new DateTime() )->format('Y');
            $anivDate->setDate(
                $currentYear - 1,
                $anivDate->format('m'),
                $anivDate->format('d')
            );

            //dump($this->historic);
            $arrayIT = new ArrayIterator($this->historic);

            do {
                $nextDate = $this->historic[ $arrayIT->key() + 1 ] ?? $anivDate;

                //dump($nextDate, $arrayIT->key(), $arrayIT->current());
                ///** @var Entry $entry
                foreach ($this->entries as $entry) {
                    //dump($entry->getDate() >= $nextDate, $entry->getDate(), $nextDate, $entry->getAmount(), $amount, '----');
                    $entryIsOnCurrentYear = $entry->getDate()->format('Y') == $currentYear;
                    if ($entry->getDate() >= $nextDate && $entry->getAmount() < 0 && $entryIsOnCurrentYear) {
                        $amount += $entry->getAmount();
                    }
                }

                $arrayIT->next();
            } while ($arrayIT->valid());

            //foreach ( $this->historic as $k => $item ) {
            //    $nextDate = $this->historic[ ++$k ] ?? new DateTime( '2019-05-01 00:00:00' );
            //
            //        dump($nextDate, $k, $item);
            //        /** @var \App\Entity\Entry $entry
         	//				foreach ( $this->entries as $entry ) {
         	//					if ( $entry->getDate() >= $nextDate )
         	//						$amount += $entry->getAmount();
         	//				}
         	//		}


            return round($amount, 2);
        }*/


        // ---

        //#[ORM\PreUpdate]
        //public function onUpdate(): void
        //{
        //    if (empty($this->historic)
        //         || (!empty($this->historic) && $this->amount != end($this->historic)[ 'amount' ])) {
        //        $this->historic[] = [
        //            'date'   => new DateTime(),
        //            'amount' => round($this->amount, 2)
        //        ];
        //    }
        //}

        public function getEnable(): bool
        {
            return $this->enable;
        }

        public function setEnable(bool $enable): self
        {
            $this->enable = $enable;

            return $this;
        }
        
        public function getProgress(): float {
            return array_reduce(
                $this->entries->toArray(),
                fn( float $currentSum, Entry $entry ): float => $currentSum + $entry->getAmount(),
                0
            );
        }
    }
