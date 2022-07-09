<?php

namespace App\Repository;

use App\Entity\Rome;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Rome|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rome|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rome[]    findAll()
 * @method Rome[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RomeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rome::class);
    }

    // /**
    //  * @return Rome[] Returns an array of Rome objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Rome
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
