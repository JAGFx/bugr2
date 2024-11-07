<?php

namespace App\Domain\PeriodicEntry\Repository;

use App\Domain\Entry\Model\EntryTypeEnum;
use App\Domain\PeriodicEntry\Entity\PeriodicEntry;
use App\Domain\PeriodicEntry\Form\PeriodicEntrySearchCommand;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class PeriodicEntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PeriodicEntry::class);
    }

    public function create(PeriodicEntry $entry, bool $flush = true): self
    {
        $this->_em->persist($entry);

        if ($flush) {
            $this->_em->flush();
        }

        return $this;
    }

    public function update(PeriodicEntry $entry, bool $flush = true): void
    {
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(PeriodicEntry $entry, bool $flush = true): self
    {
        $this->_em->remove($entry);

        if ($flush) {
            $this->_em->flush();
        }

        return $this;
    }

    public function searchValueObject(?PeriodicEntrySearchCommand $command = null): QueryBuilder
    {
        $command ??= new PeriodicEntrySearchCommand();

        $queryBuilder = $this
            ->createQueryBuilder('p')
            ->select(
                'NEW App\Domain\PeriodicEntry\ValueObject\PeriodicEntryValueObject(
                    p.id, 
                    p.name, 
                    IFNULL(SUM(b.amount / 12), p.amount), 
                    p.executionDate, 
                    IF(SUM(b.amount) IS NULL,FALSE,TRUE),
                    IF(SUM(b.amount) IS NULL,NULL,COUNT(DISTINCT b)),
                    a.name
                )'
            )
            ->leftJoin('p.budgets', 'b')
            ->leftJoin('p.account', 'a')
            ->groupBy('p.id')
            ->andWhere('IF(b IS NULL,TRUE,b.enable) = TRUE');

        if (EntryTypeEnum::TYPE_SPENT === $command->getEntryTypeEnum()) {
            $queryBuilder->andHaving('SUM(b.amount) IS NULL');
        }

        if (EntryTypeEnum::TYPE_FORECAST === $command->getEntryTypeEnum()) {
            $queryBuilder->andHaving('SUM(b.amount) IS NOT NULL');
        }

        return $queryBuilder;
    }
}
