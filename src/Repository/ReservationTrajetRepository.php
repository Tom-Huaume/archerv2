<?php

namespace App\Repository;

use App\Entity\ReservationTrajet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReservationTrajet|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReservationTrajet|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReservationTrajet[]    findAll()
 * @method ReservationTrajet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationTrajetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReservationTrajet::class);
    }

    // /**
    //  * @return ReservationTrajet[] Returns an array of ReservationTrajet objects
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
    public function findOneBySomeField($value): ?ReservationTrajet
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
