<?php

namespace App\Repository;

use App\Entity\Documents;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Documents>
 *
 * @method Documents|null find($id, $lockMode = null, $lockVersion = null)
 * @method Documents|null findOneBy(array $criteria, array $orderBy = null)
 * @method Documents[]    findAll()
 * @method Documents[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Documents::class);
    }

//    /**
//     * @return Documents[] Returns an array of Documents objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Documents
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }



/**
     * @param string $query
     * @return Documents[]
     */
    public function searchDocuments($query): array
{
    return $this->createQueryBuilder('d')
        ->leftJoin('d.idtopic', 't') // Assuming 'topic' is the association in Documents
        ->where('d.documentName LIKE :query')
        ->orWhere('d.niveau LIKE :query')
        ->orWhere('d.semestre LIKE :query')
        ->orWhere('d.documentType LIKE :query')
        ->orWhere('t.topicName LIKE :query')
        ->setParameter('query', '%' . $query . '%')
        ->getQuery()
        ->getResult();
}

      /**
     * Get all documents associated with a specific user2.
     *
     * @param User2 $user
     * @return array
     */
    public function findDocumentsByUser(User2 $user): array
    {
        return $this->createQueryBuilder('d')
            ->where('d.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }
    public function getDistinctLevels(): array
    {
        return $this->createQueryBuilder('d')
            ->select('DISTINCT d.niveau')
            ->getQuery()
            ->getResult();
    }

  
    public function getDocumentsCountBySemester(): array
{
    return $this->createQueryBuilder('d')
        ->select('d.semestre as semester, COUNT(d.id) as document_count')
        ->groupBy('d.semestre')
        ->getQuery()
        ->getResult();
}
public function findAllDocuments()
{
    return $this->createQueryBuilder('d')
        ->getQuery()
        ->getResult();
}
public function countDocumentsByNiveau()
{
    return $this->createQueryBuilder('d')
        ->select('d.niveau, COUNT(d.id) as count')
        ->groupBy('d.niveau')
        ->getQuery()
        ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
}
}

    


