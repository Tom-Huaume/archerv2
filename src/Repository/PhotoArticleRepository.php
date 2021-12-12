<?php

namespace App\Repository;

use App\Entity\PhotoArticle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PhotoArticle|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhotoArticle|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhotoArticle[]    findAll()
 * @method PhotoArticle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhotoArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PhotoArticle::class);
    }

    // /**
    //  * @return PhotoArticle[] Returns an array of PhotoArticle objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PhotoArticle
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
