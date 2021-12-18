<?php

namespace App\Controller;

use App\Entity\ReservationTrajet;
use App\Repository\MembreRepository;
use App\Repository\ReservationTrajetRepository;
use App\Repository\TrajetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

class TrajetController extends AbstractController
{
    #[Route('/user/trajet/reservation/{id}', name: 'trajet_reservation')]
    public function reservation(
        int $id,
        MembreRepository $membreRepository,
        TrajetRepository $trajetRepository,
        ReservationTrajetRepository $reservationTrajetRepository,
        EntityManagerInterface $entityManager,
    ): Response
    {
        //Récupérer user
        $userId = $this->getUser()->getId();
        $membre = $membreRepository->findOneBy(array('id' => $userId));

        //Retourner une erreur 401 si le membre n'est pas actif
        if(!$membre->getStatutLicence()){
            return new Response("Votre statut est inactif !", 401);
        }

        //Récupérer trajet et évènement
        $trajet = $trajetRepository->findOneBy(array('id'=> $id));

        //suppression si réservation existante dans la base (unique membre trajet)
        $reservation = $reservationTrajetRepository->findOneBy(array(
            'membre' => $membre,
            'trajet' => $trajet
        ));
        if($reservation != null){

            $entityManager->remove($reservation);
            $entityManager->flush();
            return new Response("Suppression du trajet", 200);

        }



        //Comme il n'esiste pas de réservation : création d'un réservation
        $reservationTrajet = new ReservationTrajet();
        $reservationTrajet->setMembre($membre);
        $reservationTrajet->setTrajet($trajet);
        $reservationTrajet->setValidation(0);
        $reservationTrajet->setDateHeureReservation(new \DateTime('now'));

        //Persister les données
        $entityManager->persist($reservationTrajet);
        $entityManager->flush();

        return new Response("Réservation du trajet", 200);
    }

    #[Route('/user/trajet/demandes/{id}', name: 'trajet_demandes')]
    public function validation(
        int $id,
        MembreRepository $membreRepository,
        TrajetRepository $trajetRepository,
        EntityManagerInterface $entityManager,
    ): Response
    {
        $userId = $this->getUser()->getId();
        $trajet = $trajetRepository->findOneBy(array('id'=> $id));
        $evenementId = $trajet->getEvenement()->getId();

        if($trajet->getOrganisateur()->getId() != $userId){
            $this->addFlash('danger', 'Impossible ! vous n\'êtes pas l\'organisateur' );
            return $this->redirectToRoute('evenement_detail', ['id' => $evenementId]);
        }

        //todo: consommer les places disponibles
        //todo: afficher les passagers validés

        return $this->render('etape/demandesTrajet.html.twig', [
            'id' => $trajet->getId(),
        ]);
    }
}
