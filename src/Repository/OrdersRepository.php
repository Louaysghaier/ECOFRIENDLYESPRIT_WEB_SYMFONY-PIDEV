<?php

namespace App\Repository;

use App\Entity\Orders;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Orders|null find($id, $lockMode = null, $lockVersion = null)
 * @method Orders|null findOneBy(array $criteria, array $orderBy = null)
 * @method Orders[] findAll()
 * @method Orders[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrdersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Orders::class);
    }
     /**
     * Find orders by user ID.
     *
     * @param int $userId
     * @return Orders[]
     */
    public function findOrdersByUser(int $userId): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.userid = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    }
    public function findOrdersByUserAndStatus(int $userId, string $status): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.userid = :userId')
            ->andWhere('o.status = :status')
            ->setParameter('userId', $userId)
            ->setParameter('status', $status)
            ->getQuery()
            ->getResult();
    }
    public function calculateSumOfPaidOrders(): float
    {
        return (float) $this->createQueryBuilder('o')
            ->select('SUM(o.priceorder)') // Assuming there is a property named totalAmount
            ->where('o.status = :status')
            ->setParameter('status', 'wanted') 
            ->getQuery()
            ->getSingleScalarResult();
    }
    public function countOrdersForUser($userId)
    {
        return $this->createQueryBuilder('o')
            ->select('COUNT(o.orderid)')
            ->where('o.userid = :userId')
            ->andWhere('o.status = :status')
            ->setParameter('userId', $userId)
            ->setParameter('status', 'wanted')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
