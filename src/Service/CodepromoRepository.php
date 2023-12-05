<?php

namespace App\Repository;

use App\Entity\Codepromo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Codepromo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Codepromo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Codepromo[] findAll()
 * @method Codepromo[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CodepromoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Codepromo::class);
    }
}
