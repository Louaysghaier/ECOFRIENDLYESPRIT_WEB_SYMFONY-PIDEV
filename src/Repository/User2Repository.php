<?php

namespace App\Repository;

use App\Entity\User2;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User2|null find($id, $lockMode = null, $lockVersion = null)
 * @method User2|null findOneBy(array $criteria, array $orderBy = null)
 * @method User2[] findAll()
 * @method User2[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class User2Repository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User2::class);
    }
    public function verif(string $email, string $password): ?User2
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.mailuser = :email')
            ->andWhere('u.mdpuser = :password')
            ->setParameter('email', $email)
            ->setParameter('password', $password)
            ->getQuery()
            ->getOneOrNullResult();
    }
    public function save(User2 $user, bool $flush = true): void
    {
        $this->getEntityManager()->persist($user);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function remove(User2 $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneBySomeField($value): ?User2
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    public function findAllAlphabeticalOrder(): array
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.nomuser', 'ASC') 
            ->getQuery()
            ->getResult();
    }
    

   
    public function searchusers(string $nomuser): array
{
    return $this->createQueryBuilder('d')
        ->andWhere('d.nomuser LIKE :nomuser')  
        ->setParameter('nomuser', '%' . $nomuser . '%')
        ->getQuery()
        ->getResult();
}
}

