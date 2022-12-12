<?php
    
    namespace App\Domain\Budget\Repository;
    
    use App\Domain\Budget\Entity\Budget;
    use App\Domain\Budget\Model\Search\BudgetSearchCommand;
    use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
    use Doctrine\ORM\QueryBuilder;
    use Doctrine\Persistence\ManagerRegistry;

    class BudgetRepository extends ServiceEntityRepository
    {
        public function __construct(ManagerRegistry $registry)
        {
            parent::__construct($registry, Budget::class);
        }
        
        public function search(BudgetSearchCommand $command): QueryBuilder
        {
            $qb = $this->createQueryBuilder('b');
            
            return $qb;
        }
        
        public function searchValueObject(BudgetSearchCommand $command): QueryBuilder
        {
            $qb = $this->createQueryBuilder('b')
                       ->select(
                           'NEW App\Domain\Budget\ValueObject\BudgetValueObject(b.id, b.name, b.amount, b.enable, SUM(e.amount) )'
                       )
                       ->join('b.entries', 'e')
                       ->groupBy('b.id');
            
            if ( !is_null($command->getFrom()) && !is_null($command->getTo())) {
                $qb->andWhere('e.createdAt BETWEEN :from AND :to')
                   ->setParameters([
                       'from' => $command->getFrom()->format('Y-m-d H:i:s'),
                       'to'   => $command->getTo()->format('Y-m-d H:i:s'),
                   ]);
            }
            
            return $qb;
        }
    }
