<?php

namespace App\Controller;

use App\Entity\InscriptionEtape;
use App\Form\InscriptionEtapeType;
use App\Repository\EtapeRepository;
use App\Repository\EvenementRepository;
use App\Repository\InscriptionEtapeRepository;
use App\Repository\MembreRepository;
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

        //Générer le formulaire inscrptionEtape
        $inscriptionEtapeForm = $this->createForm(InscriptionEtapeType::class, null);
        $inscriptionEtapeForm->handleRequest($request);

        if($inscriptionEtapeForm->isSubmitted() && $inscriptionEtapeForm->isValid()){

            //interdiction de l'inscription si l'utilisateur est inactif
            if($membre->getStatutLicence() == 0){
                $this->addFlash('danger', 'Impossible! Votre licence n\'est pas valide' );
                return $this->redirectToRoute('evenement_detail', ['id' => $evenementId]);
            }

            //Récupérer la valeur du commentaire
            $commentaire = $inscriptionEtapeForm["commentaire"]->getData();

            //Instancier l'entité InscriptionEtape
            $inscription = new InscriptionEtape();
            $inscription->setCommentaire($commentaire);
            $inscription->setEtape($etape);
            $inscription->setMembre($membre);
            $inscription->setValidation(0);
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

    #[Route('/gestion/etapes/validation/{id}', name:'etapes_validation')]
    public function validation(
        int $id,
        InscriptionEtapeRepository $inscriptionEtapeRepository,
        EntityManagerInterface $entityManager
    ): Response
    {
        //récupérer inscription, étape et évènement associé
        $inscription = $inscriptionEtapeRepository->findOneBy(array('id' => $id));
        $evenementId = $inscription->getEtape()->getEvenement()->getId();
        $etape = $inscription->getEtape();
        $nbPlaces = $etape->getNbInscriptionsMax();

        //switch valeur validation (booléen)
        if($inscription->getValidation() == 0)
        {
            //redirection si nbPlace = 0
            if($nbPlaces <= 0 && !is_null($nbPlaces)){
                $this->addFlash('danger', 'Pas de place disponible !' );
                return $this->redirectToRoute('etapes_liste_inscrits', ['id' => $evenementId]);
            }
            //inscrire et décrémenter nb places si non null
            $inscription->setValidation(1);
            if(!is_null($nbPlaces)){
                $nbPlaces--;
                $etape->setNbInscriptionsMax($nbPlaces);
            }

        } elseif ($inscription->getValidation() == 1)
        {
            //désinscrire et incrémenter nb places si non null
            $inscription->setValidation(0);
            if(!is_null($nbPlaces)) {
                $nbPlaces = $nbPlaces + 1;
                $etape->setNbInscriptionsMax($nbPlaces);
            }
        }

        //Persister les données
        $entityManager->persist($inscription);
        $entityManager->flush();

        return $this->redirectToRoute('etapes_liste_inscrits', ['id' => $evenementId]);
    }

    #[Route('/gestion/etapes/confirmation/{id}', name:'etapes_confirmation', methods: ["GET", "POST", "PUT"])]
    public function confirmation(
        int $id
    ) : Response
    {
        file_put_contents('test.txt', $id);
        return new Response();
    }

    #[Route('/gestion/inscription/desistement/{id}', name:'etape_desistement')]
    public function desistement(
        int $id,
        EtapeRepository $etapeRepository,
        InscriptionEtapeRepository $inscriptionEtapeRepository,
        EntityManagerInterface $entityManager
    ): Response
    {
        //récupérer inscription, étape et évènement associé
        $etape = $etapeRepository->findOneBy(array('id' => $id));
        $user = $this->getUser();
        $inscription = $inscriptionEtapeRepository->findOneBy(array('membre' => $user, 'etape' => $etape));
        $evenementId = $etape->getEvenement()->getId();
        $nbPlaces = $etape->getNbInscriptionsMax();

        //Si l'inscription a été validée et qu'un nombre de places max a été défini
        if ($inscription->getValidation() == 1 && !is_null($nbPlaces)){

            //Incrémenter le nombre de places
            $nbPlaces++;
            $etape->setNbInscriptionsMax($nbPlaces);
            $entityManager->persist($etape);

        }

        //Persister les données
        $entityManager->remove($inscription);
        $entityManager->flush();

        //if($nbPlaces <= 0 && !is_null($nbPlaces)){


        return $this->redirectToRoute('evenement_detail', ['id' => $evenementId]);
    }


//    #[Route('/gestion/etapes/confirmation/{id}', name:'etapes_confirmation')]
//    public function confirm(
//        int $id,
//        InscriptionEtapeRepository $inscriptionEtapeRepository,
//        EntityManagerInterface $entityManager
//    ): Response
//    {
//        //todo: demander si il faut envoyer les confirmations par mail
//
//        return $this->redirectToRoute('etapes_liste_inscrits', ['id' => $evenementId]);
//    }
}
