<?php

namespace App\Domain\Budget\Repository;

use App\Domain\Budget\Entity\Budget;
use App\Domain\Budget\Model\Search\BudgetSearchCommand;
use App\Domain\Entry\Model\EntryKindEnum;
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
        $queryBuilder = $this->createQueryBuilder('b')
            ->orderBy('b.name');

        if (null !== $command->getName()) {
            $queryBuilder->andWhere('b.name = :name')
                ->setParameter('name', $command->getName());
        }

        if (true === $command->getEnabled()) {
            $queryBuilder->andWhere('b.enable = TRUE');
        }

        return $queryBuilder;
    }

    public function searchValueObject(BudgetSearchCommand $command): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('b')
            ->select(
                'NEW App\Domain\Budget\ValueObject\BudgetValueObject(b.id, b.name, b.amount, b.enable, SUM(e.amount))'
            )
            ->join('b.entries', 'e')
            ->groupBy('b.id');

        if (null !== $command->getYear()) {
            $queryBuilder->andWhere('e.createdAt BETWEEN :from AND :to')
                ->setParameter('from', YearRange::firstDayOf($command->getYear())->format('Y-m-d H:i:s'))
                ->setParameter('to', YearRange::lastDayOf($command->getYear())->format('Y-m-d H:i:s'));
        }

        if (true === $command->getShowCredits()) {
            $queryBuilder->andWhere('e.amount > 0')
                ->andWhere('e.kind = :kind')
                ->setParameter('kind', EntryKindEnum::DEFAULT);
        }

        if (false === $command->getShowCredits()) {
            $queryBuilder->andWhere('e.amount < 0')
                ->andWhere('e.kind = :kind')
                ->setParameter('kind', EntryKindEnum::DEFAULT);
        }

        if (null !== $command->getName()) {
            $queryBuilder->andWhere('b.name = :name')
                ->setParameter('name', $command->getName());
        }

        if (null !== $command->getBudgetId()) {
            $queryBuilder->andWhere('b.id = :budget')
                ->setParameter('budget', $command->getBudgetId());
        }

        return $queryBuilder;
    }
}
