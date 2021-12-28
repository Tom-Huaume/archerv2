<?php

namespace App\Repository;

use App\Entity\Evenement;
use App\Entity\Lieu;
use App\Entity\Membre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Evenement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evenement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evenement[]    findAll()
 * @method Evenement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvenementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evenement::class);
    }

    public function findFutureEvents($dateDuJour){
        $queryBuilder = $this->createQueryBuilder('e')
            ->where('e.dateHeureDebut > :dateDuJour')
            ->setParameter('dateDuJour', $dateDuJour)
            ->orderBy('e.dateHeureDebut', 'ASC');

        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }

    public function findPastEvents($dateDuJour){
        $queryBuilder = $this->createQueryBuilder('e')
            ->where('e.dateHeureDebut < :dateDuJour')
            ->setParameter('dateDuJour', $dateDuJour)
            ->orderBy('e.dateHeureDebut', 'DESC');

        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }


    public function findEventsOfUser($userId){
        $queryBuilder = $this->createQueryBuilder('e')
            ->setParameter('userId', $userId)
            ->innerJoin('e.etapes', 'et')
            ->innerJoin('et.inscriptionEtapes', 'i')
            ->innerJoin('i.membre', 'm')
            ->where('m.id = :userId');

        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }

    public function findFutureEventsOfUser($userId, $dateDuJour){
        $queryBuilder = $this->createQueryBuilder('e')
            ->setParameter('userId', $userId)
            ->setParameter('dateDuJour', $dateDuJour)
            ->innerJoin('e.etapes', 'et')
            ->innerJoin('et.inscriptionEtapes', 'i')
            ->innerJoin('i.membre', 'm')
            ->where('m.id = :userId')
            ->andWhere('e.dateHeureDebut > :dateDuJour');

        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }



//    public function findEventsFromDate(){
//        $sql = "SELECT * FROM evenement;";
//
//        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
//        $rsm->addEntityResult(Evenement::class, "e");
//        //on mappe le nom de chaque colonne en BDD
//        foreach ($this->getClassMetadata()->fieldMappings as $obj){
//            $rsm->addFieldResult('e', 'id','id');
//            $rsm->addJoinedEntityResult(Lieu::class, 'l', 'e', 'lieuDestination');
//            $rsm->addFieldResult('l', 'lieu_destination_id','id');
//            $rsm->addFieldResult('e', 'nom','nom');
//            $rsm->addFieldResult('e', 'description','description');
//            $rsm->addFieldResult('e', 'date_heure_debut','dateHeureDebut');
//            $rsm->addFieldResult('e', 'date_heure_limite_inscription','dateHeureLimiteInscription');
//            $rsm->addFieldResult('e', 'nb_inscriptions_max','nbInscriptionsMax');
//            $rsm->addFieldResult('e', 'etat','etat');
//            $rsm->addFieldResult('e', 'tarif','tarif');
//            $rsm->addFieldResult('e', 'photo','photo');
//            $rsm->addFieldResult('e', 'date_heure_creation','dateHeureCreation');
//            $rsm->addFieldResult('e', 'date_heure_fin','dateHeureFin');
//        }
//
//        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
//        return $query->getResult();
//    }


    // /**
    //  * @return Evenement[] Returns an array of Evenement objects
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
    public function findOneBySomeField($value): ?Evenement
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
