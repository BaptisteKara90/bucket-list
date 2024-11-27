<?php

namespace App\Repository;

use App\Entity\Wish;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Wish>
 */
class WishRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Wish::class);
    }

    public function getWishesSortByDate(){
        $qd = $this->createQueryBuilder('w');
           $qd->addOrderBy('w.dateCreated', 'DESC');
           $query = $qd->getQuery();
           return $query->getResult();
    }
}
