<?php

namespace App\Controller;

use App\Entity\Membre;
use App\Form\MembreType;
use App\Form\UpdateMembreType;
use App\Repository\MembreRepository;
use App\Service\UpdateManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class MembreController extends AbstractController
{
    /**
     * @throws Exception
     */
    #[Route('/gestion/membre', name: 'membre_list')]
    public function list(
        Request $request,
        EntityManagerInterface $entityManager,
        MembreRepository $membreRepository,
        UserPasswordHasherInterface $passwordHasher,
        UpdateManager $updateManager
    ): Response
    {
        //liste des lieux
        $membres = $membreRepository->findAll();

        //générer le formulaire de création dans la vue
        $membre = new Membre();
        $membreForm = $this->createForm(MembreType::class, $membre);
        $membreForm->handleRequest($request);

        //générer le formulaire d'upload excel
        $uploadForm = $this->createForm(UpdateMembreType::class, null);
        $uploadForm->handleRequest($request);

        //traitement de l'upload par xls, xlsx ou csv
        if($uploadForm->isSubmitted() && $uploadForm->isValid())
        {
            //récupérer fichier + répertoire de destionation
            $uploads_directory = $this->getParameter('update_directory'); //dans config/services.yaml
            $file = $uploadForm['fichier']->getData();

            //définir le nom du fichier
            $fileName='liste.csv';

            //suppression de l'ancien fichier si il a été conservé par erreur
            $filesystem = new Filesystem();
            $filesystem->remove($uploads_directory.DIRECTORY_SEPARATOR.$fileName);

            //enregistrer le fichier
            $file->move(
                $uploads_directory,
                $fileName
            );

            //todo: faire un retour à la page si le fichier est vide
            //todo: export excel
            //todo: boutons actif et supprimer en AJAX
            //todo: prévoire le cas où une colonne non nullable serait vide
            //Lancement de la mise à jour des données membes
            $updateManager->updateMembresParTableur($fileName, $uploads_directory);

            $filesystem->remove($uploads_directory.DIRECTORY_SEPARATOR.$fileName);

            $this->addFlash('success', 'Base de données mise à jour !');
            return $this->redirectToRoute('membre_list');

        }

        //traitement du formulaire
        if($membreForm->isSubmitted() && $membreForm->isValid())
        {

            //todo: mot de passe aléatoire pour les nouveaux
            $membre->setPassword($passwordHasher->hashPassword($membre, random_bytes(200)));
            $membre->setStatutLicence(1);
            $membre->setRoles(array('ROLE_USER'));
            $entityManager->persist($membre);
            $entityManager->flush();

            $this->addFlash('success', 'Membre enregistré');
            return $this->redirectToRoute('membre_list');
        }

        return $this->render('membre/list.html.twig', [
            'membreForm' => $membreForm->createView(),
            'uploadForm' => $uploadForm->createView(),
            'membres' => $membres,
        ]);
    }

    #[Route('/gestion/membre/update', name: 'membre_update')]
    public function update(){



    }

    #[Route('/gestion/membre/supprimer/{id}', name:'membre_delete')]
    public function delete(
        Membre $membre,
        EntityManagerInterface $entityManager
    ): Response
    {
        if($membre->getRoles() == array("ROLE_ADMIN") || $membre->getRoles() == array("ROLE_SECRETAIRE")){
            $this->addFlash('danger', 'Impossible de supprimer un membre secrétaire ou admin');
            return $this->redirectToRoute('membre_list');
        }

        if(!$membre->getTrajets()->isEmpty()){
            $this->addFlash('danger', 'Impossible ! Ce membre a créé des trajets');
            return $this->redirectToRoute('membre_list');
        }

        if(!$membre->getReservationTrajets()->isEmpty()){
            $this->addFlash('danger', 'Impossible ! Ce membre a réservé des trajets');
            return $this->redirectToRoute('membre_list');
        }

        if(!$membre->getInscriptionEtapes()->isEmpty()){
            $this->addFlash('danger', 'Impossible ! Ce membre est inscrit a une étape');
            return $this->redirectToRoute('membre_list');
        }

        $entityManager->remove($membre);
        $entityManager->flush();

        $this->addFlash('danger', 'Membre supprimé');
        return $this->redirectToRoute('membre_list');
    }

    #[Route('/gestion/membre/activer/{id}', name:'membre_activate')]
    public function activer(
        int $id,
        MembreRepository $membreRepository,
        EntityManagerInterface $entityManager
    ): Response
    {
        $membre = $membreRepository->findOneBy(array('id' => $id));

        if($membre->getStatutLicence() == false){
            $membre->setStatutLicence(true);
        } else {
            $membre->setStatutLicence(false);
        }

        $entityManager->persist($membre);
        $entityManager->flush();

        $this->addFlash('info', 'Statut modifié');
        return $this->redirectToRoute('membre_list');
    }

    #[Route('/gestion/membre/activer2/{id}', name:'membre_activate2', methods: ["PUT"])]
    public function activer2(
        int $id,
        MembreRepository $membreRepository,
        EntityManagerInterface $entityManager
    ): Response
    {
        $membre = $membreRepository->findOneBy(array('id' => $id));

        if($membre->getStatutLicence() == false){
            $membre->setStatutLicence(true);
        } else {
            $membre->setStatutLicence(false);
        }

        $entityManager->persist($membre);
        $entityManager->flush();

        $this->addFlash('info', 'Statut modifié');
        return $this->redirectToRoute('membre_list');
    }

    #[Route('/admin/membre/modifier/{id}', name:'membre_update')]
    public function modifier(
        int $id,
        MembreRepository $membreRepository,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response
    {

        //générer le formulaire de modif dans la vue
        $membre = $membreRepository->findOneBy(array('id' => $id));
        $membreForm = $this->createForm(MembreType::class, $membre);
        $membreForm->handleRequest($request);

        if($membreForm->isSubmitted() && $membreForm->isValid()){

            //redirection si le membre est admin ou secrétaire
            if($membre->getRoles() == array("ROLE_ADMIN") || $membre->getRoles() == array("ROLE_SECRETAIRE")){
                $this->addFlash('danger', 'Impossible de modifier un membre secrétaire ou admin');
                return $this->redirectToRoute('membre_list');
            }

            //MAJ BDD
            $entityManager->persist($membre);
            $entityManager->flush();

            $this->addFlash('info', 'Profil modifié');
            return $this->redirectToRoute('membre_list');
        }


        return $this->render('/admin/membre/profil.html.twig', [
            'membre' => $membre,
            'membreForm' => $membreForm->createView()
        ]);
    }

    #[Route('/membre/modifier', name:'profil_update')]
    public function profil(
        EntityManagerInterface $entityManager,
        Request $request
    ): Response
    {

        //générer le formulaire de modif dans la vue
        $membre = $this->getUser();
        $membreForm = $this->createForm(MembreType::class, $membre);
        $membreForm->handleRequest($request);

        if($membreForm->isSubmitted() && $membreForm->isValid()){

            //MAJ BDD
            $entityManager->persist($membre);
            $entityManager->flush();

            $this->addFlash('info', 'Votre profil a bien été modifié');
            return $this->redirectToRoute('main_home');
        }


        return $this->render('/membre/profil.html.twig', [
            'membre' => $membre,
            'membreForm' => $membreForm->createView()
        ]);
    }

    #[Route('/admin/membre/affecter/{id}', name:'profil_grant')]
    public function affecter(
        int $id,
        MembreRepository $membreRepository,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response
    {

        $membre = $membreRepository->findOneBy(array('id' => $id));

        //générer le formulaire de modif dans la vue
        if($this->getUser()->getRoles() == array("ROLE_ADMIN")){

            //switch role
            if($membre->getRoles() == array("ROLE_SECRETAIRE")){
                $membre->setRoles(array("ROLE_USER"));
            }elseif ($membre->getRoles() == array("ROLE_USER")){
                $membre->setRoles(array("ROLE_SECRETAIRE"));
            }

            //MAJ BDD
            $entityManager->persist($membre);
            $entityManager->flush();

            $this->addFlash('warning', 'Statut modifié pour ce membre');
        }else{

            $this->addFlash('danger', 'Vous n\'avez pas les droits nécessaires');
        }
        return $this->redirectToRoute('membre_update', ['id' => $id]);

    }
}
