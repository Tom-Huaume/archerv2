<?php

namespace App\Repository;

use App\Entity\Membre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @method Membre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Membre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Membre[]    findAll()
 * @method Membre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MembreRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Membre::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof Membre) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    //Liste des membres qui ont fait une demande d'inscription à l'évènement
    public function findMembresOfEvent($eventId){
        $queryBuilder = $this->createQueryBuilder('m')
            ->setParameter('eventId', $eventId)
            ->innerJoin('m.inscriptionEtapes', 'i')
            ->innerJoin('i.etape', 'et')
            ->innerJoin('et.evenement', 'ev')
            ->where('ev.id = :eventId');

        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }

    //Lignes si le membre a organisé un trajet pour l'évènement
    public function findMembreTrajetsPourEvenement($membreId, $eventId){
        $queryBuilder = $this->createQueryBuilder('m')
            ->setParameter('eventId', $eventId)
            ->setParameter('membreId', $membreId)
            ->innerJoin('m.trajets', 't')
            ->innerJoin('t.evenement', 'e')
            ->where('e.id = :eventId')
            ->andWhere('m.id = :membreId');

        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }

    //Lignes si le membre a réservé un trajet pour l'évènement
    public function findMembreReservationTrajetPourEvenement($membreId, $eventId){
        $queryBuilder = $this->createQueryBuilder('m')
            ->setParameter('eventId', $eventId)
            ->setParameter('membreId', $membreId)
            ->innerJoin('m.reservationTrajets', 'rt')
            ->innerJoin('rt.trajet', 't')
            ->innerJoin('t.evenement', 'e')
            ->where('e.id = :eventId')
            ->andWhere('m.id = :membreId');

        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }

    //Liste des membres inscrits à l'évènement
    public function findMembreAcceptesPourEvenement($eventId){
        $queryBuilder = $this->createQueryBuilder('m')
            ->setParameter('eventId', $eventId)
            ->innerJoin('m.inscriptionEtapes', 'i')
            ->innerJoin('i.etape', 'et')
            ->innerJoin('et.evenement', 'ev')
            ->where('ev.id = :eventId')
            ->andWhere('i.validation = 1');

        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }


    /**
     * @throws NonUniqueResultException
     */
    public function findReservationsOf($idMembre){
        $sql = "SELECT id, prenom FROM Membre WHERE id = ?;";

        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addEntityResult(Membre::class, 'm');

        foreach ($this->getClassMetadata()->fieldMappings as $obj){
            $rsm->addFieldResult('m', 'id', 'id');
            //$rsm->addFieldResult('m', 'prenom', 'prenom');
        }


        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameter(1, $idMembre);

        return $query->getOneOrNullResult();
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findAdmin($role){
        $queryBuilder = $this->createQueryBuilder('m')
            ->where('m.roles LIKE :roles')
            ->setParameter('roles', '%"'.$role.'"%');

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    // /**
    //  * @return Membre[] Returns an array of Membre objects
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
    public function findOneBySomeField($value): ?Membre
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
