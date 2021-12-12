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
    #[Route('/trajet/reservation/{id}', name: 'trajet_reservation')]
    public function reservation(
        int $id,
        MembreRepository $membreRepository,
        TrajetRepository $trajetRepository,
        EntityManagerInterface $entityManager,
    ): Response
    {
        //Récupérer user, trajet et évènement
        //todo : Modifier user par défaut
        $user = $membreRepository->findOneBy(array('id' => 1));
        $trajet = $trajetRepository->findOneBy(array('id'=> $id));
        $evenementId = $trajet->getEvenement()->getId();

        //
        $reservationTrajet = new ReservationTrajet();
        $reservationTrajet->setMembre($user);
        $reservationTrajet->setTrajet($trajet);
        $reservationTrajet->setValidation(0);
        $reservationTrajet->setDateHeureReservation(new \DateTime('now'));

        //Persister les données
        $entityManager->persist($reservationTrajet);
        $entityManager->flush();

        $this->addFlash('success', 'Demande de réservation envoyée' );
        return $this->redirectToRoute('evenement_detail', ['id' => $evenementId]);
    }
}
