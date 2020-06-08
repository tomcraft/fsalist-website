<?php

namespace App\Repository;

use App\Entity\MediaComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MediaComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method MediaComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method MediaComment[]    findAll()
 * @method MediaComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediaCommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MediaComment::class);
    }

    // /**
    //  * @return MediaComment[] Returns an array of MediaComment objects
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
    public function findOneBySomeField($value): ?MediaComment
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
