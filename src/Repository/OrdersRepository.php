<?php

namespace App\Repository;

use App\Entity\Orders;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
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
    public function calculateSumOfPaidOrders(int $userId): float
    {
        return (float) $this->createQueryBuilder('o')
            ->select('SUM(o.priceorder)') // Assuming there is a property named totalAmount
            ->where('o.status = :status')
            ->andWhere('o.userid = :userId')
            ->setParameter('status', 'wanted')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getSingleScalarResult();
    }
    public function statistique(): float
    {
        return (float) $this->createQueryBuilder('o')
            ->select('SUM(o.priceorder)')
            ->where('o.status = :status')
            ->setParameter('status', 'wanted')
            ->getQuery()
            ->getSingleScalarResult();
    }
    public function PaidOrders(int $userId): float
    {
        return (float) $this->createQueryBuilder('o')
            ->select('SUM(o.priceorder)') // Assuming there is a property named totalAmount
            ->where('o.status = :status')
            ->andWhere('o.userid = :userId')
            ->setParameter('status', 'payed')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getSingleScalarResult();
    }
    public function countOrdersForUser(int $userId)
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
    public function getTotalOrdersPerDay(): array
    {
        return $this->createQueryBuilder('o')
            ->select('COUNT(o) as totalOrders', 'o.pickupdatetime as orderDate')
            ->groupBy('orderDate')
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);
    }

    public function getAverageOrderPrice(): ?float
    {
        $query = $this->createQueryBuilder('o')
            ->select('AVG(o.priceorder) as averagePrice')
            ->getQuery();

        return $query->getSingleScalarResult();
    }
}
