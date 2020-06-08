<?php

namespace App\Repository;

use App\Entity\MediaReview;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MediaReview|null find($id, $lockMode = null, $lockVersion = null)
 * @method MediaReview|null findOneBy(array $criteria, array $orderBy = null)
 * @method MediaReview[]    findAll()
 * @method MediaReview[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediaReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MediaReview::class);
    }

    // /**
    //  * @return MediaReview[] Returns an array of MediaReview objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MediaReview
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
