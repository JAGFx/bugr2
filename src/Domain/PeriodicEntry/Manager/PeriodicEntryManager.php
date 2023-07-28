<?php

namespace App\Domain\PeriodicEntry\Manager;

use App\Domain\Budget\Entity\Budget;
use App\Domain\Entry\Entity\Entry;
use App\Domain\PeriodicEntry\Entity\PeriodicEntry;
use App\Domain\PeriodicEntry\Form\PeriodicEntrySearchCommand;
use App\Domain\PeriodicEntry\Repository\PeriodicEntryRepository;
use App\Domain\PeriodicEntry\ValueObject\PeriodicEntryValueObject;

class PeriodicEntryManager
{
    public function __construct(
        private readonly PeriodicEntryRepository $periodicEntryRepository
    ) {
    }

    public function create(PeriodicEntry $entry, bool $flush = true): void
    {
        $this->periodicEntryRepository->create($entry, $flush);
    }

    public function update(PeriodicEntry $entry, bool $flush = true): void
    {
        $this->periodicEntryRepository->update($entry, $flush);
    }

    public function remove(PeriodicEntry $entry, bool $flush = true): void
    {
        $this->periodicEntryRepository->remove($entry, $flush);
    }

    /** @return PeriodicEntryValueObject[] */
    public function searchValueObject(PeriodicEntrySearchCommand $command = null): array
    {
        $command ??= new PeriodicEntrySearchCommand();

        /** @var PeriodicEntryValueObject[] $periodicEntriesValueObjects */
        $periodicEntriesValueObjects = $this->periodicEntryRepository
            ->searchValueObject($command)
            ->getQuery()
            ->getResult();

        return $periodicEntriesValueObjects;
    }
}
