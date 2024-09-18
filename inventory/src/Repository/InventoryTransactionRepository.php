<?php

namespace App\Repository;

use App\Entity\InventoryTransaction;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class InventoryTransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InventoryTransaction::class);
    }

    public function countAllTransaction($type): int
    {
        $qb = $this->createQueryBuilder('p');

        if ($type !== null) {
            $qb->where('p.transactionType = :type')
                ->setParameter('type', $type)
                ->setParameter('type', $type)
                ->select('COUNT(p.id)');
        } else {
            $qb->select('COUNT(p.id)');
        }

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    public function queryByInventoryAndTransactionTypeInPeriod($inventoryId, $transactionType, $from, $to, $limit = 20, $offset = 0)
    {
        $qb = $this->createQueryBuilder('p');
        if ($inventoryId !== null) {
            $qb->andWhere('p.inventory = :inventoryId')
                ->setParameter('inventoryId', $inventoryId);
        }
        if ($transactionType !== null) {
            $qb->andWhere('p.transactionType = :transactionType')
                ->setParameter('transactionType', $transactionType);
        }
        if ($from !== null && $to !== null) {
            $qb->andWhere('p.transactionDate >= :from')
                ->andWhere('p.transactionDate <= :to')
                ->setParameter('from', $from)
                ->setParameter('to', $to);
        }
        if ($limit !== null) {
            $qb->setMaxResults($limit);
        }
        if ($offset !== null) {
            $qb->setFirstResult($offset);
        }
        return $qb->getQuery()->getResult();
    }
}
