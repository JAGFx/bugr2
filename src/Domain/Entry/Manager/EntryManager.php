<?php

namespace App\Domain\Entry\Manager;

use App\Domain\Entry\Entity\Entry;
use App\Domain\Entry\Repository\EntryRepository;
use App\Domain\Entry\ValueObject\EntryBalance;
use App\Shared\Utils\Statistics;

class EntryManager
{
    public function __construct(
        private readonly EntryRepository $entryRepository
    ) {
    }

    public function balance(): EntryBalance
    {
        $data = $this->entryRepository
            ->balance()
            ->getQuery()
            ->getResult();

        $spentAmount = Statistics::filterBy($data, 'id', null);
        $forecastAmount = Statistics::filterBy($data, 'id', null, true);

        $spentAmount = Statistics::sumOf($spentAmount, 'sum');
        $forecastAmount = Statistics::sumOf($forecastAmount, 'sum');

        return new EntryBalance($spentAmount + $forecastAmount, $spentAmount, $forecastAmount);
    }

    public function create(Entry $entry): void {
        $this->entryRepository->create($entry);
    }
}
