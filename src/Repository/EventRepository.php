<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[] findAll()
 * @method Event[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }



    public function searchByName(string $query)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.nomevent LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->getQuery()
            ->getResult();
    }
    
/////////////////////////
    public function countTotalParticipantsByEventType($eventType)
    {
        return $this->createQueryBuilder('e')
            ->select('SUM(e.NbParticipants) as totalParticipants')
            ->where('e.typeevent = :eventType')
            ->setParameter('eventType', $eventType)
            ->getQuery()
            ->getSingleScalarResult();
    }

  ////////////////
  public function findValidEvents()
  {
      return $this->createQueryBuilder('e')
          ->where('e.valid = 1')
          ->getQuery()
          ->getResult();
  }

  public function findNonValidEvents()
  {
      return $this->createQueryBuilder('e')
          ->where('e.valid = 0')
          ->getQuery()
          ->getResult();
  }



  public function findTopPopularEvents()
    {
        $sportPopularEvents = $this->findPopularEvents('Sport', 3);
        $loisirPopularEvents = $this->findPopularEvents('Loisir', 3);
        $culturePopularEvents = $this->findPopularEvents('Culture', 3);

        return [
            'Sport' => $sportPopularEvents,
            'Loisir' => $loisirPopularEvents,
            'Culture' => $culturePopularEvents,
        ];
    }


   


    public function countEventsByCategory(): array
    {
        $queryBuilder = $this->createQueryBuilder('e')
            ->select('COUNT(e.idevent) as count')
            ->addSelect('SUM(CASE WHEN e.typeevent = :culture THEN 1 ELSE 0 END) as cultureCount')
            ->addSelect('SUM(CASE WHEN e.typeevent = :loisir THEN 1 ELSE 0 END) as loisirCount')
            ->addSelect('SUM(CASE WHEN e.typeevent = :sport THEN 1 ELSE 0 END) as sportCount')
            ->setParameter('culture', 'Culture')
            ->setParameter('loisir', 'Loisir')
            ->setParameter('sport', 'Sport');
    
        $result = $queryBuilder->getQuery()->getSingleResult();
    
        return [
            'Culture' => $result['cultureCount'],
            'Loisir' => $result['loisirCount'],
            'Sport' => $result['sportCount'],
        ];
    }
    






}
