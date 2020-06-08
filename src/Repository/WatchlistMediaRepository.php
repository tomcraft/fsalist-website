<?php

namespace App\Repository;

use App\Entity\WatchlistMedia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method WatchlistMedia|null find($id, $lockMode = null, $lockVersion = null)
 * @method WatchlistMedia|null findOneBy(array $criteria, array $orderBy = null)
 * @method WatchlistMedia[]    findAll()
 * @method WatchlistMedia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WatchlistMediaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WatchlistMedia::class);
    }

    // /**
    //  * @return WatchlistMedia[] Returns an array of WatchlistMedia objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WatchlistMedia
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
