<?php

    namespace App\Domain\PeriodicEntry\Repository;

    use App\Domain\PeriodicEntry\Entity\PeriodicEntry;
    use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
    use Doctrine\Persistence\ManagerRegistry;

    class PeriodicEntryRepository extends ServiceEntityRepository
    {
        public function __construct(ManagerRegistry $registry)
        {
            parent::__construct($registry, PeriodicEntry::class);
        }
    }
