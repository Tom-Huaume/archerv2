<?php

namespace App\Controller;

use App\Entity\InscriptionEtape;
use App\Form\InscriptionEtapeType;
use App\Repository\EtapeRepository;
use App\Repository\EvenementRepository;
use App\Repository\MembreRepository;
use Doctrine\ORM\EntityManagerInterface;
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
        //todo : modifier le user par défaut
        //Récupérer user, étape et évenement concernés
        $user = $membreRepository->findOneBy(array('id' => 1));
        $etape = $etapeRepository->findOneBy(array('id' => $id));
        $evenementId = $etape->getEvenement()->getId();

        //Générer le formulaire inscrptionEtape
        $inscriptionEtapeForm = $this->createForm(InscriptionEtapeType::class, null);
        $inscriptionEtapeForm->handleRequest($request);

        if($inscriptionEtapeForm->isSubmitted() && $inscriptionEtapeForm->isValid()){

            //Récupérer la valeur du commentaire
            $commentaire = $inscriptionEtapeForm["commentaire"]->getData();

            //Instancier l'entité InscriptionEtape
            $inscription = new InscriptionEtape();
            $inscription->setCommentaire($commentaire);
            $inscription->setEtape($etape);
            $inscription->setMembre($user);
            $inscription->setValidation(0);
            $inscription->setDateHeureInscription(new \DateTime('now'));

            //Persister les données
            $entityManager->persist($inscription);
            $entityManager->flush();

            $this->addFlash('success', 'Demande d\'inscription envoyée' );
            return $this->redirectToRoute('evenement_detail', ['id' => $evenementId]);

        }

        return $this->render('etape/inscriptionEtape.html.twig', [
            'evenementId' => $evenementId,
            'inscriptionEtapeForm'=>$inscriptionEtapeForm->createView()
        ]);
    }

    #[Route('/gestion/etapes/validation/{id}', name:'etapes_validation')]
    public function validation(
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

}
