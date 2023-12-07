<?php

namespace App\Repository;

use App\Entity\Ticket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ticket>
 *
 * @method Ticket|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ticket|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ticket[]    findAll()
 * @method Ticket[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TicketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ticket::class);
    }
    /**
 * Trouver les tickets disponibles en fonction des critères de réservation.
 *
 * @param array $reservationData
 * @return Ticket[]|array
 */
public function findAvailableTickets(array $reservationData): array
{
    $qb = $this->createQueryBuilder('t')
        ->andWhere('t.lieuDepart = :lieuDepart')
        ->setParameter('lieuDepart', $reservationData['lieuDepart'])
        ->andWhere('t.lieuArrive = :lieuArrive')
        ->setParameter('lieuArrive', $reservationData['lieuArrive'])
        ->andWhere('t.dateTicket = :dateTicket')
        ->setParameter('dateTicket', $reservationData['dateTicket'])
        ->getQuery();

    // Exécutez la requête et retournez les résultats
    return $qb->getResult();
}



public function countTotalTickets(): int
{
    return $this->createQueryBuilder('t')
        ->select('COUNT(t.id)')
        ->getQuery()
        ->getSingleScalarResult();
}

public function calculateAveragePrice(): float
{
    return $this->createQueryBuilder('t')
        ->select('AVG(t.prix)')
        ->getQuery()
        ->getSingleScalarResult();
}


public function findTicketWithHighestPrice()
{
    return $this->createQueryBuilder('t')
        ->orderBy('t.prix', 'DESC')
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult();
}

public function findTicketWithLowestPrice()
{
    return $this->createQueryBuilder('t')
        ->orderBy('t.prix', 'ASC')
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult();
}

public function calculateTicketStatusPercentage(): array
{
    $qb = $this->createQueryBuilder('t')
        ->select('t.statutTicket, COUNT(t.id) AS ticketCount')
        ->groupBy('t.statutTicket')
        ->getQuery();

    $results = $qb->getResult();

    $totalTickets = 0;
    $statusCounts = [];

    // Calculer le nombre total de tickets et les compter par statut
    foreach ($results as $result) {
        $status = $result['statutTicket'];
        $count = $result['ticketCount'];

        $totalTickets += $count;
        $statusCounts[$status] = $count;
    }

    $percentages = [];

    // Calculer le pourcentage de chaque statut de ticket
    foreach ($statusCounts as $status => $count) {
        $percentage = ($count / $totalTickets) * 100;
        $percentages[$status] = $percentage;
    }

    return $percentages;
}
//    /**
//     * @return Ticket[] Returns an array of Ticket objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Ticket
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
