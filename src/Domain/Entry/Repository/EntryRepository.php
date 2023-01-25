<?php

namespace App\Domain\Entry\Repository;

use App\Domain\Entry\Entity\Entry;
use App\Domain\Entry\Model\EntrySearchCommand;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class EntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Entry::class);
    }

    public function create(Entry $entry): self
    {
        $this->_em->persist($entry);

        return $this;
    }

    public function flush(): void
    {
        $this->_em->flush();
    }

    public function balance(): QueryBuilder
    {
        return $this->createQueryBuilder('e')
            ->select('SUM(e.amount) as sum, b.id')
            ->leftJoin('e.budget', 'b')
            ->groupBy('b.id');
    }

    public function getEntryQueryBuilder(EntrySearchCommand $command): QueryBuilder {
        return $this->createQueryBuilder('e');
    }
}
