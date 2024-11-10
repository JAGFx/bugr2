<?php

namespace App\Domain\Budget\Repository;

use App\Domain\Budget\Entity\HistoryBudget;
use App\Domain\Budget\Model\Search\BudgetSearchCommand;
use App\Shared\Utils\YearRange;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class HistoryBudgetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HistoryBudget::class);
    }

    public function getAvailableYear(): QueryBuilder
    {
        return $this
            ->createQueryBuilder('hb')
            ->select('DISTINCT YEAR(hb.date) AS year')
            ->orderBy('year', 'DESC');
    }

    public function search(BudgetSearchCommand $command): QueryBuilder
    {
        $qb = $this->createQueryBuilder('hb');

        if (!is_null($command->getYear())) {
            $qb
                ->andWhere('hb.date BETWEEN :from AND :to')
                ->setParameter('from', YearRange::firstDayOf($command->getYear())->format('Y-m-d H:i:s'))
                ->setParameter('to', YearRange::lastDayOf($command->getYear())->format('Y-m-d H:i:s'));
        }

        return $qb;
    }
}
