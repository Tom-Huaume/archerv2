<?php

namespace App\Controller;

use App\Entity\ReservationTrajet;
use App\Repository\MembreRepository;
use App\Repository\TrajetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrajetController extends AbstractController
{
    #[Route('/user/trajet/reservation/{id}', name: 'trajet_reservation')]
    public function reservation(
        int $id,
        MembreRepository $membreRepository,
        TrajetRepository $trajetRepository,
        EntityManagerInterface $entityManager,
    ): Response
    {
        //Récupérer user, trajet et évènement
        $userId = $this->getUser()->getId();
        $membre = $membreRepository->findOneBy(array('id' => $userId));
        $trajet = $trajetRepository->findOneBy(array('id'=> $id));
        $evenementId = $trajet->getEvenement()->getId();

        //
        $reservationTrajet = new ReservationTrajet();
        $reservationTrajet->setMembre($membre);
        $reservationTrajet->setTrajet($trajet);
        $reservationTrajet->setValidation(0);
        $reservationTrajet->setDateHeureReservation(new \DateTime('now'));

        //Persister les données
        $entityManager->persist($reservationTrajet);
        $entityManager->flush();

        $this->addFlash('success', 'Demande de réservation envoyée' );
        return $this->redirectToRoute('evenement_detail', ['id' => $evenementId]);
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
