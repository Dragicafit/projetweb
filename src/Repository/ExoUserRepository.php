<?php

namespace App\Repository;

use App\Entity\ExoUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ExoUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExoUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExoUser[]    findAll()
 * @method ExoUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExoUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExoUser::class);
    }

    // /**
    //  * @return ExoUser[] Returns an array of ExoUser objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ExoUser
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
