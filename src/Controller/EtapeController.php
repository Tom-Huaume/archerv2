<?php

namespace App\Controller;

use App\Entity\InscriptionEtape;
use App\Form\InscriptionEtapeType;
use App\Repository\EtapeRepository;
use App\Repository\EvenementRepository;
use App\Repository\InscriptionEtapeRepository;
use App\Repository\MembreRepository;
use App\Repository\TrajetRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Void_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EtapeController extends AbstractController
{
    #[Route('/user/etape/inscription/{id}', name: 'etape_inscription')]
    public function inscription(
        int $id,
        Request $request,
        EntityManagerInterface $entityManager,
        EtapeRepository $etapeRepository,
        MembreRepository $membreRepository,
    ): Response
    {
        //Récupérer user, étape et évenement concernés
        $userId = $this->getUser()->getId();
        $membre = $membreRepository->findOneBy(array('id' => $userId));
        $etape = $etapeRepository->findOneBy(array('id' => $id));
        $evenementId = $etape->getEvenement()->getId();

        //Récupérer la liste des désignations d'arme
        $listeArmes = $etape->getArmes()->toArray();
        $listeDesignationArmes = array();
        foreach ($listeArmes as $a) {
            $listeDesignationArmes[$a->getDesignation()] = $a->getDesignation();
        }
        //dd($listeDesignationArmes);

        //Générer le formulaire inscrptionEtape
        $inscriptionEtapeForm = $this->createForm(InscriptionEtapeType::class, $listeDesignationArmes);
        $inscriptionEtapeForm->handleRequest($request);
        if($inscriptionEtapeForm->isSubmitted() && $inscriptionEtapeForm->isValid()){


            //interdiction de l'inscription si l'utilisateur est inactif
            if($membre->getStatutLicence() == 0){
                $this->addFlash('danger', 'Impossible! Votre licence n\'est pas valide' );
                return $this->redirectToRoute('evenement_detail', ['id' => $evenementId]);
            }

            //Récupérer la valeur du commentaire
            $commentaire = $inscriptionEtapeForm["commentaire"]->getData();
            $armeChoisie = $inscriptionEtapeForm["listeArmes"]->getData();

            //Instancier l'entité InscriptionEtape
            $inscription = new InscriptionEtape();
            $inscription->setCommentaire($commentaire);
            $inscription->setArme($armeChoisie);
            $inscription->setEtape($etape);
            $inscription->setMembre($membre);
            $inscription->setValidation(null); //null correspond au staut non traité
            $inscription->setDateHeureInscription(new \DateTime('now'));

            //Persister les données
            $entityManager->persist($inscription);
            $entityManager->flush();
            //todo: déplacer le message flash
            $this->addFlash('success', 'Demande d\'inscription envoyée' );
            return $this->redirectToRoute('evenement_detail', ['id' => $evenementId]);

        }

        return $this->render('etape/inscriptionEtape.html.twig', [
            'evenementId' => $evenementId,
            'inscriptionEtapeForm'=>$inscriptionEtapeForm->createView()
        ]);
    }

    #[Route('/gestion/etapes/inscrits/{id}', name:'etapes_liste_inscrits')]
    public function list(
        int $id,
        EvenementRepository $evenementRepository
    ): Response
    {
        $evenement = $evenementRepository->findOneBy(array('id' => $id));
        $etapes = $evenement->getEtapes();

        return $this->render('etape/validationEtapes.html.twig', [
            'evenement' => $evenement,
            'etapes' => $etapes,

        ]);
    }

    #[Route('/gestion/etapes/refuse/{id}', name:'etape_refuser', methods: ["GET", "PUT", "POST"])]
    public function refuse(
        int $id,
        InscriptionEtapeRepository $inscriptionEtapeRepository,
        EntityManagerInterface $entityManager
    ) : Response
    {
        $inscription = $inscriptionEtapeRepository->findOneBy(array('id' => $id));

        if($inscription->getValidation() == null)
        {
            $inscription->setValidation(0);
            $userName = "--".$this->getUser()->getPrenom()." ".$this->getUser()->getNom();
            $inscription->setValidateur($userName);
            $inscription->setDateHeureValidation(new \DateTime());

            //Persister les données
            $entityManager->persist($inscription);
            $entityManager->flush();
        }

        return new Response("success", 200);
    }

    //todo: envoyer les confirmations par mail
    #[Route('/gestion/etapes/accepte/{id}', name:'etape_accepter', methods: ["GET", "PUT", "POST"])]
    public function accepte(
        int $id,
        InscriptionEtapeRepository $inscriptionEtapeRepository,
        EntityManagerInterface $entityManager
    ) : Response
    {
        //récupérer inscription, étape et évènement associé
        $inscription = $inscriptionEtapeRepository->findOneBy(array('id' => $id));
        $evenementId = $inscription->getEtape()->getEvenement()->getId();
        $etape = $inscription->getEtape();
        $nbPlaces = $etape->getNbInscriptionsMax();

        //switch valeur validation (booléen)
        if($inscription->getValidation() == 0 || $inscription->getValidation() == null)
        {
            //redirection si nbPlace = 0
            if($nbPlaces <= 0 && !is_null($nbPlaces)){
                return new Response("Not enough places", 304);
                }
            //inscrire et décrémenter nb places si non null
            $inscription->setValidation(1);
            if(!is_null($nbPlaces)){
                $nbPlaces--;
                $etape->setNbInscriptionsMax($nbPlaces);
                $userName = "--".$this->getUser()->getPrenom()." ".$this->getUser()->getNom();
                $inscription->setValidateur($userName);
                $inscription->setDateHeureValidation(new \DateTime());
            }

        } elseif ($inscription->getValidation() == 1)
        {
            //désinscrire et incrémenter nb places si non null
            $inscription->setValidation(0);
            if(!is_null($nbPlaces)) {
                $nbPlaces = $nbPlaces + 1;
                $etape->setNbInscriptionsMax($nbPlaces);
                $userName = "--".$this->getUser()->getPrenom()." ".$this->getUser()->getNom();
                $inscription->setValidateur($userName);
                $inscription->setDateHeureValidation(new \DateTime());
            }
        }

        //Persister les données
        $entityManager->persist($inscription);
        $entityManager->flush();

        return new Response("success", 200);
    }

    #[Route('/user/inscription/desistement/{id}', name:'etape_desistement')]
    public function desistement(
        int $id,
        EtapeRepository $etapeRepository,
        InscriptionEtapeRepository $inscriptionEtapeRepository,
        EntityManagerInterface $entityManager,
        MembreRepository $membreRepository
    ): Response
    {
        //récupérer inscription, étape et évènement associé
        $etape = $etapeRepository->findOneBy(array('id' => $id));
        $user = $this->getUser();
        $inscription = $inscriptionEtapeRepository->findOneBy(array('membre' => $user, 'etape' => $etape));
        $evenementId = $etape->getEvenement()->getId();

        //Redirection si le user est dans la liste des membres qui ont organisé ou réservé un trajet pour cet évènement
        $membresOrganisateurs = $membreRepository->findMembreTrajetsPourEvenement($user->getId(), $evenementId);
        $membresPassagers = $membreRepository->findMembreReservationTrajetPourEvenement($user->getId(), $evenementId);
        if(in_array($user, $membresPassagers) or in_array($user, $membresOrganisateurs)){
            $this->addFlash('danger', 'Impossible de vous désister si vos participez ou avez proposé un trajet' );
            return $this->redirectToRoute('evenement_detail', ['id' => $evenementId]);
        }

        //Désistement accepté si la demande d'inscription n'est pas traitée
        if(is_null($inscription->getValidation())){
            $entityManager->remove($inscription);
            $entityManager->flush();

        }elseif($inscription->getValidation() == 0){    //redirection si traitée mais refusée
            $this->addFlash('danger', 'Votre demande d\'inscription a déjà été refusée' );
            return $this->redirectToRoute('evenement_detail', ['id' => $evenementId]);

        }elseif ($inscription->getValidation() == 1){   //redirection si la demande a déjà été acceptée
            $this->addFlash('danger', 'Votre demande d\'inscription a déjà été acceptée' );
            return $this->redirectToRoute('evenement_detail', ['id' => $evenementId]);

        }

        return $this->redirectToRoute('evenement_detail', ['id' => $evenementId]);
    }

}
