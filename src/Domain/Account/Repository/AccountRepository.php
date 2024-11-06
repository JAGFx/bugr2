<?php

namespace App\Domain\Account\Repository;

use App\Domain\Account\Entity\Account;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AccountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Account::class);
    }

    public function create(Account $account): self
    {
        $this->_em->persist($account);

        return $this;
    }

    public function flush(): void
    {
        $this->_em->flush();
    }
}
