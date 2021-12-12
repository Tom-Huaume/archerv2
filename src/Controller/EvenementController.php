<?php

namespace App\Controller;

use App\Entity\Etape;
use App\Entity\Evenement;
use App\Entity\Lieu;
use App\Entity\Membre;
use App\Entity\Trajet;
use App\Form\EtapeType;
use App\Form\EvenementType;
use App\Form\InscriptionEtapeType;
use App\Form\LieuType;
use App\Form\TrajetType;
use App\Repository\EtapeRepository;
use App\Repository\EvenementRepository;
use App\Repository\LieuRepository;
use App\Repository\MembreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EvenementController extends AbstractController
{
    #[Route('/evenement/liste', name: 'evenement_list')]
    public function list(
        EvenementRepository $evenementRepository
    ): Response
    {

        //générer le formulaire de création d'évènement
        $evenements = $evenementRepository->findAll();

        return $this->render('evenement/list.html.twig', [
            'evenements' => $evenements,
        ]);
    }

    #[Route('/admin/evenement/create', name: 'evenement_create')]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {

        //générer le formulaire de création d'évènement
        $evenementForm = $this->createForm(EvenementType::class, null);
        $evenementForm->handleRequest($request);

        //Traitement du formulaire
        if($evenementForm->isSubmitted() && $evenementForm->isValid())
        {

            //Récupérer les données du formulaire evenement
            $lieuDestination = $evenementForm["lieuDestination"]->getData();
            $nom = $evenementForm["nom"]->getData();
            $description = $evenementForm["description"]->getData();
            $dateHeureDebut = $evenementForm["dateHeureDebut"]->getData();
            $dateHeureLimiteInscription = $evenementForm["dateHeureLimiteInscription"]->getData();
            $nbInscriptionsMax = $evenementForm["nbInscriptionsMax"]->getData();
            $tarif = $evenementForm["tarif"]->getData();
            $photo = $evenementForm["photo"]->getData();

            if(!$lieuDestination instanceof Lieu){

                //Récupérer les données du formulaire lieu
                $nomlieu = $evenementForm["nomlieu"]->getData();
                $rue = $evenementForm["rue"]->getData();
                $rue2 = $evenementForm["rue2"]->getData();
                $codePostal = $evenementForm["codePostal"]->getData();
                $ville = $evenementForm["ville"]->getData();

                //Vérification des données lieu
                if($rue == null){
                    $this->addFlash('danger', 'Veuillez saisir un lieu');
                    return $this->redirectToRoute('evenement_create');
                }
                if($codePostal == null){
                    $this->addFlash('danger', 'Une adresse doit avoir un code postal !');
                    return $this->redirectToRoute('evenement_create');
                }
                if($ville == null){
                    $this->addFlash('danger', 'Une adresse doit avoir une ville !');
                    return $this->redirectToRoute('evenement_create');
                }

                //Créer instance de lieu
                $lieu = new Lieu();
                $lieu->setNom($nomlieu);
                $lieu->setRue($rue);
                $lieu->setRue2($rue2);
                $lieu->setCodePostal($codePostal);
                $lieu->setVille($ville);
                $lieu->setClub(0);
                $lieu->setList(1);

                $entityManager->persist($lieu);
                //$entityManager->flush();

                $lieuDestination = $lieu;
            }

            //Créer instance d'evenement
            $evenement = new Evenement();
            $evenement->setLieuDestination($lieuDestination);
            $evenement->setNom($nom);
            $evenement->setDescription($description);
            $evenement->setDateHeureDebut($dateHeureDebut);
            $evenement->setDateHeureLimiteInscription($dateHeureLimiteInscription);
            $evenement->setNbInscriptionsMax($nbInscriptionsMax);
            $evenement->setTarif($tarif);
            $evenement->setPhoto($photo);
            $evenement->setEtat("Ouvert");
            $evenement->setDateHeureCreation(new \DateTime('now'));

            //Persister les données
            $entityManager->persist($evenement);
            $entityManager->flush();

            //Récupérer l'id
            $id = $evenement->getId();

            $this->addFlash('success', 'Evènement engristré');
            return $this->redirectToRoute('evenement_detail', ['id' => $id]);
        }

        return $this->render('evenement/create.html.twig', [
            'evenementForm' => $evenementForm->createView(),
        ]);

    }


    #[Route('/evenement/{id}', name: 'evenement_detail')]
    public function detail(
        int $id,
        EvenementRepository $evenementRepository,
        LieuRepository $lieuRepository,
        MembreRepository $membreRepository,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response
    {
        //Générer le formulaire étape
        $etape = new Etape();
        $etapeForm = $this->createForm(EtapeType::class, $etape);
        $etapeForm->handleRequest($request);

        //Générer le formulaire trajet
        $trajet = new Trajet();
        $trajetForm = $this->createForm(TrajetType::class, $trajet);
        $trajetForm->handleRequest($request);

        //Récupérer l'entité évènement à traiter + étapes et trajets associés
        $evenement = $evenementRepository->findOneBy(array('id' => $id));
        $etapes = $evenement->getEtapes();
        $trajets = $evenement->getTrajets();

        //Traitement du formulaire étape
        if($etapeForm->isSubmitted() && $etapeForm->isValid()){

            //Ajouter champs manquants
            $etape->setDateHeureCreation(new \DateTime());
            $etape->setEvenement($evenement);

            //MAJ BDD
            $entityManager->persist($etape);
            $entityManager->flush();
            $this->addFlash('success', 'Etape ajoutée ! Les membres peuvent s\'inscrire');
            return $this->redirectToRoute('evenement_detail', ['id' => $id]);
        }

        //Traitement du formulaire trajet
        if($trajetForm->isSubmitted() && $trajetForm->isValid()){

            //Ajouter champs manquants
            $trajet->setDateHeureCreation(new \DateTime());
            $trajet->setEvenement($evenement);
            // todo: modifier l'organisateur par défaut
            $organisateurProvisoire = $membreRepository->findOneBy(array('id' => 1));
            $trajet->setOrganisateur($organisateurProvisoire);

            //Récupérer adresse du club
            $adresseClub = $lieuRepository->findOneBy(array('club' => 1));

            //Récupérer le choix de la liste déroulante (Adresse départ)
            $clubDefaut = $trajetForm["clubDefaut"]->getData();
            $trajet->setLieuDepart($adresseClub);

            //Si l'adresse du club par défaut n'est pas choisie on prends les champs saisis par l'utilisateur
            if($clubDefaut == false){

                //Récupérer données lieu non mappées
                $nomLieuDepart = $trajetForm["nomLieuDepart"]->getData();
                $rueLieuDepart = $trajetForm["rueLieuDepart"]->getData();
                $rue2LieuDepart = $trajetForm["rue2LieuDepart"]->getData();
                $codePostalLieuDepart = $trajetForm["codePostalLieuDepart"]->getData();
                $villeLieuDepart = $trajetForm["villeLieuDepart"]->getData();

                //Vérification des données lieu
                if($rueLieuDepart == null){
                    $this->addFlash('danger', 'Veuillez saisir un lieu');
                    return $this->redirectToRoute('evenement_detail', ['id' => $id]);
                }
                if($codePostalLieuDepart == null){
                    $this->addFlash('danger', 'Une adresse doit avoir un code postal !');
                    return $this->redirectToRoute('evenement_detail', ['id' => $id]);
                }
                if($villeLieuDepart == null){
                    $this->addFlash('danger', 'Une adresse doit avoir une ville !');
                    return $this->redirectToRoute('evenement_detail', ['id' => $id]);
                }

                //Création d'un lieu sur la base des champs saisis
                $lieuDepartCustom = new Lieu();
                $lieuDepartCustom->setNom($nomLieuDepart);
                $lieuDepartCustom->setRue($rueLieuDepart);
                $lieuDepartCustom->setRue2($rue2LieuDepart);
                $lieuDepartCustom->setCodePostal($codePostalLieuDepart);
                $lieuDepartCustom->setVille($villeLieuDepart);
                $lieuDepartCustom->setClub(0);
                $lieuDepartCustom->setList(0);

                $entityManager->persist($lieuDepartCustom);
                //$entityManager->flush();

                $trajet->setLieuDepart($lieuDepartCustom);

            }

            //MAJ BDD
            $entityManager->persist($trajet);
            $entityManager->flush();
            $this->addFlash('success', 'Trajet ajouté ! Les membres peuvent réserver');
            return $this->redirectToRoute('evenement_detail', ['id' => $id]);
        }

        return $this->render('evenement/detail.html.twig', [
            'evenement'=>$evenement,
            'etapes'=>$etapes,
            'trajets'=>$trajets,
            'etapeForm'=>$etapeForm->createView(),
            'trajetForm'=>$trajetForm->createView(),
        ]);
    }

}
