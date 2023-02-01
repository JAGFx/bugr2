<?php

namespace App\Domain\Entry\Manager;

use App\Domain\Entry\Entity\Entry;
use App\Domain\Entry\Model\EntrySearchCommand;
use App\Domain\Entry\Repository\EntryRepository;
use App\Domain\Entry\ValueObject\EntryBalance;
use App\Shared\Utils\Statistics;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class EntryManager
{
    public function __construct(
        private readonly EntryRepository $entryRepository,
        private readonly PaginatorInterface $paginator
    ) {
    }

    public function balance(): EntryBalance
    {
        /** @var array<string, mixed> $data */
        $data = $this->entryRepository
            ->balance()
            ->getQuery()
            ->getResult();

        $spentAmount    = Statistics::filterBy($data, 'id', null);
        $forecastAmount = Statistics::filterBy($data, 'id', null, true);

        $spentAmount    = Statistics::sumOf($spentAmount, 'sum');
        $forecastAmount = Statistics::sumOf($forecastAmount, 'sum');

        return new EntryBalance($spentAmount + $forecastAmount, $spentAmount, $forecastAmount);
    }

    public function create(Entry $entry): void
    {
        $this->entryRepository->create($entry)
            ->flush();
    }

    /**
     * @return Entry[]
     */
    public function search(?EntrySearchCommand $command = null): array
    {
        $command ??= new EntrySearchCommand();

        /** @var Entry[] $result */
        $result = $this->entryRepository->getEntryQueryBuilder($command)
            ->getQuery()
            ->getResult();

        return $result;
    }

    /**
     * @return PaginationInterface<Entry>
     */
    public function getPaginated(?EntrySearchCommand $command = null): PaginationInterface
    {
        $command ??= new EntrySearchCommand();

        return $this->paginator->paginate(
            $this->search($command),
            $command->getPage(),
            $command->getPageSize()
        );
    }
}
