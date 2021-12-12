<?php

namespace App\Repository;

use App\Entity\InscriptionEtape;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method InscriptionEtape|null find($id, $lockMode = null, $lockVersion = null)
 * @method InscriptionEtape|null findOneBy(array $criteria, array $orderBy = null)
 * @method InscriptionEtape[]    findAll()
 * @method InscriptionEtape[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InscriptionEtapeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InscriptionEtape::class);
    }

    // /**
    //  * @return InscriptionEtape[] Returns an array of InscriptionEtape objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?InscriptionEtape
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
