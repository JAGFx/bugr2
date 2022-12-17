<?php

namespace App\Domain\Budget\Repository;

use App\Domain\Budget\Entity\Budget;
use App\Domain\Budget\Model\Search\BudgetSearchCommand;
use App\Shared\Utils\YearRange;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class BudgetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Budget::class);
    }

    public function create(Budget $budget): self
    {
        $this->_em->persist($budget);

        return $this;
    }

    public function flush(): void
    {
        $this->_em->flush();
    }

    public function search(BudgetSearchCommand $command): QueryBuilder
    {
        $qb = $this->createQueryBuilder('b')
            ->orderBy('b.name');

        return $qb;
    }

    public function searchValueObject(BudgetSearchCommand $command): QueryBuilder
    {
        $qb = $this->createQueryBuilder('b')
            ->select(
                'NEW App\Domain\Budget\ValueObject\BudgetValueObject(b.id, b.name, b.amount, b.enable, SUM(e.amount))'
            )
            ->join('b.entries', 'e')
            ->groupBy('b.id');

        if (null !== $command->getYear()) {
            $qb->andWhere('e.createdAt BETWEEN :from AND :to')
                ->setParameters([
                    'from' => YearRange::fisrtDayOf($command->getYear())->format('Y-m-d H:i:s'),
                    'to' => YearRange::lastDayOf($command->getYear())->format('Y-m-d H:i:s'),
                ]);
        }

        if (true === $command->getShowCredits()) {
            $qb->andWhere('e.amount > 0');
        }

        if (false === $command->getShowCredits()) {
            $qb->andWhere('e.amount < 0');
        }

        return $qb;
    }
}
