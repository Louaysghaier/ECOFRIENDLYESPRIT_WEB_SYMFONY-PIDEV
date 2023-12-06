<?php

namespace App\Repository;

use App\Entity\Participation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Participation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Participation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Participation[] findAll()
 * @method Participation[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticipationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Participation::class);
    }


// EventRepository.php

public function findByEventId($eventId)
{
    return $this->createQueryBuilder('e')
        ->join('e.event', 'p')  // Change 'participations' to 'event'
        ->where('e.id = :eventId')
        ->setParameter('eventId', $eventId)
        ->getQuery()
        ->getResult();
}




public function countParticipationsByUser()
{
    return $this->createQueryBuilder('p')
        ->select('COUNT(p.id) as totalParticipations, u.name as username')
        ->leftJoin('p.user', 'u')
        ->groupBy('u.id')
        ->getQuery()
        ->getResult();
}

public function updatePaymentStatusByEventId($eventId)
{
    $qb = $this->createQueryBuilder('p')
        ->update()
        ->set('p.paymentStatus', ':paymentStatus')
        ->where('p.event = :eventId')
        ->setParameter('paymentStatus', false)
        ->setParameter('eventId', $eventId);

    return $qb->getQuery()->execute();
}












}
