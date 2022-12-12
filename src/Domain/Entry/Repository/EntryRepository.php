<?php

namespace App\Domain\Entry\Repository;

use App\Domain\Entry\Entity\Entry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class EntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Entry::class);
    }

    // public function balance(string $type): float
    // {
    //    return (float) $this
    //        ->createQueryBuilder('e')
    //        ->select('SUM( e.amount ) as sumAmount')
    //        ->where('e.type = :type')
    //        ->setParameter('type', $type)
    //        ->getQuery()
    //        ->getSingleScalarResult();
    // }

    public function balance(): QueryBuilder
    {
        return $this->createQueryBuilder('e')
            ->select('e.type, SUM(e.amount) as sum')
            ->groupBy('e.type');
    }
}
