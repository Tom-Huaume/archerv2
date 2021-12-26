<?php

namespace App\Controller;

use App\Entity\ReservationTrajet;
use App\Repository\EtapeRepository;
use App\Repository\EvenementRepository;
use App\Repository\InscriptionEtapeRepository;
use App\Repository\MembreRepository;
use App\Repository\ReservationTrajetRepository;
use App\Repository\TrajetRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
        EvenementRepository $evenementRepository,
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

        //Suppression si réservation existante dans la base (unique membre trajet)
        $reservation = $reservationTrajetRepository->findOneBy(array(
            'membre' => $membre,
            'trajet' => $trajet
        ));
        if($reservation != null){

            if(is_null($reservation->getValidation())){
                $entityManager->remove($reservation);
                $entityManager->flush();
                return new Response("Suppression du trajet", 200);
            }elseif ($reservation->getValidation()==0){
                return new Response("Impossible vous êtes déjà refusé", 405);
            }elseif ($reservation->getValidation()==1){
                return new Response("Impossible vous êtes déjà accepté", 405);
            }
        }

        //Redirection si le membre n'est pas inscrit à un départ
        $evenements__etapes__inscriptions__membres = $evenementRepository->findEventsOfUser($userId);
        $evenemetDuTrajet = $trajet->getEvenement();
        if(!in_array($evenemetDuTrajet, $evenements__etapes__inscriptions__membres)){

            return new Response("Vous n'êtes pas inscrit !", 406);

        }

        //Pas de réservation existante donc création d'un réservation
        $reservationTrajet = new ReservationTrajet();
        $reservationTrajet->setMembre($membre);
        $reservationTrajet->setTrajet($trajet);
        $reservationTrajet->setValidation(null);
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

    #[Route('/user/trajet/reservations', name: 'trajet_list')]
    public function list(
        EvenementRepository $evenementRepository,
        TrajetRepository $trajetRepository,
        Request $request
    ): Response
    {
        $membre = $this->getUser();
        $dateDuJour =new \DateTime();

        // Evenements liés au user
        $evenements__etapes__inscriptions__membres = $evenementRepository->findFutureEventsOfUser($membre->getId(), $dateDuJour);
        $trajets__reservations__membres = $trajetRepository->findRidesOfUser($membre->getId());
        $trajetsConducteur = $trajetRepository->findBy(array('organisateur' => $membre));
        //dd($trajetsConducteur);


        return $this->render('trajet/list.html.twig', [
            'membre' => $membre,
            'evenements__etapes__inscriptions__membres' => $evenements__etapes__inscriptions__membres,
            'trajets__reservations__membres' => $trajets__reservations__membres,
            'trajetsConducteur' => $trajetsConducteur
        ]);
    }

    #[Route('/user/trajet/refuse/{id}', name:'trajet_refuser', methods: ["GET", "PUT", "POST"])]
    public function refuse(
        int $id,
        ReservationTrajetRepository $reservationTrajetRepository,
        EntityManagerInterface $entityManager
    ) : Response
    {
        $reservation = $reservationTrajetRepository->findOneBy(array('id' => $id));
        $trajet = $reservation->getTrajet();
        $user = $this->getUser();

        //retourne une erreur si le user n'est pas l'orgaisateur du trajet
        if($trajet->getOrganisateur() !== $user){
            return new Response("Implossible ! Vous n'êtes pas l'organisateur du trajet", 403);
        }

        if($reservation->getValidation() == null)
        {
            $reservation->setValidation(0);
            $reservation->setDateHeureReservation(new \DateTime());

            //Persister les données
            $entityManager->persist($reservation);
            $entityManager->flush();
        }

        return new Response("success", 200);
    }

    //todo: envoyer les confirmations par mail
    #[Route('/user/trajet/accepte/{id}', name:'trajet_accepter', methods: ["GET", "PUT", "POST"])]
    public function accepte(
        int $id,
        ReservationTrajetRepository $reservationTrajetRepository,
        EntityManagerInterface $entityManager
    ) : Response
    {
        //récupérer réservation, trajet et user
        $reservation = $reservationTrajetRepository->findOneBy(array('id' => $id));
        $trajet = $reservation->getTrajet();
        $user = $this->getUser();
        $nbPlaces = $trajet->getNbPlaces();

        //retourne une erreur si le user n'est pas l'orgaisateur du trajet
        if($trajet->getOrganisateur() !== $user){
            return new Response("Implossible ! Vous n'êtes pas l'organisateur du trajet", 403);
        }

        //switch valeur validation (booléen)
        if($reservation->getValidation() == 0 || $reservation->getValidation() == null)
        {
            //redirection si nbPlace = 0
            if($nbPlaces <= 0 && !is_null($nbPlaces)){
                return new Response("Not enough places", 304);
            }
            //inscrire et décrémenter nb places si non null
            $reservation->setValidation(1);
            if(!is_null($nbPlaces)){
                $nbPlaces--;
                $trajet->setNbPlaces($nbPlaces);
                $reservation->setDateHeureReservation(new \DateTime());
            }

        } elseif ($reservation->getValidation() == 1)
        {
            //désinscrire et incrémenter nb places si non null
            $reservation->setValidation(0);
            if(!is_null($nbPlaces)) {
                $nbPlaces = $nbPlaces + 1;
                $trajet->setNbPlaces($nbPlaces);
                $reservation->setDateHeureReservation(new \DateTime());
            }
        }

        //Persister les données
        $entityManager->persist($reservation);
        $entityManager->flush();

        return new Response("success", 200);
    }
}
