<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findAllWithPagination(QueryBuilder $qb = null)
    {
        if ($qb === null) {
            $qb = $this->createQueryBuilder('p');
        }

        return $qb->getQuery()->getResult();
    }

    public function countAllProducts(): int
    {
        $qb = $this->createQueryBuilder('p')
            ->select('COUNT(p.id)');

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

   

}
