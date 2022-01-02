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
    #[Route('/user/evenement/liste', name: 'evenement_list')]
    public function list(
        EvenementRepository $evenementRepository
    ): Response
    {

        //générer le formulaire de création d'évènement
        $dateDuJour = new \DateTime();
        $evenements = $evenementRepository->findFutureEvents($dateDuJour);

        return $this->render('evenement/list.html.twig', [
            'evenements' => $evenements,
        ]);
    }

    #[Route('/gestion/evenement/create', name: 'evenement_create')]
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
            $dateHeureFin = $evenementForm["dateHeureFin"]->getData();
            $dateHeureLimiteInscription = $evenementForm["dateHeureLimiteInscription"]->getData();
            $nbInscriptionsMax = $evenementForm["nbInscriptionsMax"]->getData();
            $tarif = $evenementForm["tarif"]->getData();

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
                $entityManager->flush();

                $lieuDestination = $lieu;
            }

            //Créer instance d'evenement
            $evenement = new Evenement();
            $evenement->setLieuDestination($lieuDestination);
            $evenement->setNom($nom);
            $evenement->setDescription($description);
            $evenement->setDateHeureDebut($dateHeureDebut);
            $evenement->setDateHeureFin($dateHeureFin);
            $evenement->setDateHeureLimiteInscription($dateHeureLimiteInscription);
            $evenement->setNbInscriptionsMax($nbInscriptionsMax);
            $evenement->setTarif($tarif);
            $evenement->setEtat("Ouvert");
            $evenement->setDateHeureCreation(new \DateTime('now'));

            //Ajout de la photo si uploadé sinon image par défaut
            if(!$evenementForm["photo"]->isempty())
            {
                //récupérer fichier + l'envoyer dans le répertoire de destionation
                $photo = $evenementForm["photo"]->getData();
                $uploads_directory = $this->getParameter('event_directory'); //dans config/services.yaml
                //dd($uploads_directory);
                $fileName=md5(uniqid()).'.'.$photo->guessExtension();
                $photo->move(
                    $uploads_directory,
                    $fileName
                );
                $evenement->setPhoto($fileName);

            }else{
                $evenement->setPhoto("defaut1.jpg");
            }

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


    #[Route('/user/evenement/{id}', name: 'evenement_detail')]
    public function detail(
        int $id,
        EvenementRepository $evenementRepository,
        LieuRepository $lieuRepository,
        MembreRepository $membreRepository,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response
    {

        //Liste des membres inscrits à l'évènement
        $membresInscrits = $membreRepository->findMembreAcceptesPourEvenement($id);

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

        //Vérifier qu'au moins une arme est sélectionnée'
        if($etapeForm->isSubmitted()){
            if($etapeForm["arme"]->getData() == null){
                $this->addFlash('danger', 'L\'étape doit comporter au moins une arme');
                return $this->redirectToRoute('evenement_detail', ['id' => $id]);
            }
        }

        //Traitement du formulaire étape
        if($etapeForm->isSubmitted() && $etapeForm->isValid()){

            //Vérifier que l'étape débute après la date de début de lévènement
            if(!($etapeForm["dateHeureDebut"]->getData() >= $evenement->getDateHeureDebut())){
                $this->addFlash('danger', 'L\'étape doit commencer après la date de début de l\'évènement');
                return $this->redirectToRoute('evenement_detail', ['id' => $id]);
            }

            //Vérifier que l'étape commence avant la date de fin de lévènement
            if(!($etapeForm["dateHeureDebut"]->getData() <= $evenement->getDateHeureFin())){
                $this->addFlash('danger', 'L\'étape doit commencer avant la date de fin de l\'évènement');
                return $this->redirectToRoute('evenement_detail', ['id' => $id]);
            }

            //Ajouter champs manquants
            $etape->setDateHeureCreation(new \DateTime());
            $etape->setEvenement($evenement);

            //récupétrer liste des armes
            $listeArmes = $etapeForm["arme"]->getData();
            foreach ($listeArmes as $a){
                $etape->addArme($a);
            }

            //MAJ BDD
            $entityManager->persist($etape);
            $entityManager->flush();

            $this->addFlash('success', 'Etape ajoutée ! Les membres peuvent s\'inscrire');
            return $this->redirectToRoute('evenement_detail', ['id' => $id]);
        }

        //Traitement du formulaire trajet
        if($trajetForm->isSubmitted() && $trajetForm->isValid()){

            //Hydrater l'instance de trajet avec les données du formulaire
            $trajet->setDateHeureCreation(new \DateTime());
            $trajet->setEvenement($evenement);

            //Contrôle des données titre
            if ($trajetForm["titre"]->getData() == null || $trajetForm["titre"]->getData() == ''){
                $this->addFlash('danger', 'Vous devez donner un titre à votre trajet pour pouvoir l\'identifier');
                return $this->redirectToRoute('evenement_detail', ['id' => $id]);
            }
            $trajet->setTitre($trajetForm["titre"]->getData());

            //Contrôle des données date/heure départ
            if ($trajetForm["dateHeureDepart"]->getData() == null || $trajetForm["dateHeureDepart"]->getData() == ''){
                $this->addFlash('danger', 'Vous devez indiquer la date/heure de départ');
                return $this->redirectToRoute('evenement_detail', ['id' => $id]);
            }
            $trajet->setDateHeureDepart($trajetForm["dateHeureDepart"]->getData());

            //Contrôle des données nombre de places
            if ($trajetForm["nbPlaces"]->getData() == null || $trajetForm["nbPlaces"]->getData() == ''){
                $this->addFlash('danger', 'Vous devez préciser le nombre de places');
                return $this->redirectToRoute('evenement_detail', ['id' => $id]);
            }elseif ($trajetForm["nbPlaces"]->getData() <1){
                $this->addFlash('danger', 'Merci de renseigner une nombre de places supérieur à zéro');
                return $this->redirectToRoute('evenement_detail', ['id' => $id]);
            }elseif ($trajetForm["nbPlaces"]->getData() >50){
                $this->addFlash('danger', 'Merci de renseigner une nombre de places inférieur à 51');
                return $this->redirectToRoute('evenement_detail', ['id' => $id]);
            }
            $trajet->setNbPlaces($trajetForm["nbPlaces"]->getData());

            if (strlen($trajetForm["description"]->getData()) >= 255){
                $this->addFlash('danger', 'Votre description doit faire 255 caractères maximum');
                return $this->redirectToRoute('evenement_detail', ['id' => $id]);
            }
            $trajet->setDescription($trajetForm["description"]->getData());

            if (strlen($trajetForm["typeVoiture"]->getData()) >= 30){
                $this->addFlash('danger', 'Le type de voiture doit faire 30 caractères maximum');
                return $this->redirectToRoute('evenement_detail', ['id' => $id]);
            }
            $trajet->setTypeVoiture($trajetForm["typeVoiture"]->getData());

            if (strlen($trajetForm["couleurVoiture"]->getData()) >= 30){
                $this->addFlash('danger', 'La couleur de la voiture doit faire 30 caractères maximum');
                return $this->redirectToRoute('evenement_detail', ['id' => $id]);
            }
            $trajet->setCouleurVoiture($trajetForm["couleurVoiture"]->getData());

            $userId = $this->getUser()->getId();
            $membre = $membreRepository->findOneBy(array('id' => $userId));
            $trajet->setOrganisateur($membre);

            //Récupérer adresse du club
            $adresseClub = $lieuRepository->findOneBy(array('club' => 1));

            //Check si l'option adresse du club (choix par défaut) a été choisie
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
                $entityManager->flush();

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
            'membresInscrits'=>$membresInscrits
        ]);
    }

}
