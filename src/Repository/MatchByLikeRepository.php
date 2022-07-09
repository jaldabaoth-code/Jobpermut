<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\MatchByLike;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method MatchByLike|null find($id, $lockMode = null, $lockVersion = null)
 * @method MatchByLike|null findOneBy(array $criteria, array $orderBy = null)
 * @method MatchByLike[]    findAll()
 * @method MatchByLike[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MatchByLikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MatchByLike::class);
    }

    // /**
    //  * @return MatchByLike[] Returns an array of MatchByLike objects
    //  */

    public function findByUser(User $user): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.userLiker = :val')
            ->setParameter('val', $user)
            ->orWhere('m.userLiked = :val')
            ->setParameter('val', $user)
            ->orderBy('m.matchedAt', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?MatchByLike
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
