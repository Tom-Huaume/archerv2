<?php

namespace App\Controller;

use App\Entity\Arme;
use App\Form\ArmeType;
use App\Form\LieuType;
use App\Form\MembreType;
use App\Form\PhotoClubType;
use App\Repository\ArmeRepository;
use App\Repository\LieuRepository;
use App\Repository\MembreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{

    #[Route('/admin/general', name:'admin_general')]
    public function general(
        ArmeRepository $armeRepository,
        LieuRepository $lieuRepository,
        MembreRepository $membreRepository,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        //Données pour afficher les types d'arme'
        $armes = $armeRepository->findAll();

        //todo: modif photo des évènements par défaut
        //todo: edition profil de l'admin
        //Formulaire armes
        $arme = new Arme();
        $armeForm = $this->createForm(ArmeType::class, $arme);

        //Formulaire admin
        $membreAdmin = $membreRepository->findAdmin("ROLE_ADMIN");
        $adminForm = $this->createForm(MembreType::class, $membreAdmin);

        //Formulaire adresse
        $adresseClub = $lieuRepository->findHomeAddress();
        $adresseForm = $this->createForm(LieuType::class, $adresseClub);

        //Formulaire photo
        $photoForm = $this->createForm(PhotoClubType::class, null);

        $armeForm->handleRequest($request);
        $adminForm->handleRequest($request);
        $adresseForm->handleRequest($request);
        $photoForm->handleRequest($request);

        if($armeForm->isSubmitted() && $armeForm->isValid())
        {
            $entityManager->persist($arme);
            $entityManager->flush();
            $this->addFlash('success', 'Arme enregistré !');
            return $this->redirectToRoute('admin_general');
        }

        if($adminForm->isSubmitted() && $adminForm->isValid())
        {
            $entityManager->persist($membreAdmin);
            $entityManager->flush();
            $this->addFlash('success', 'Profil admin modifié !');
            return $this->redirectToRoute('admin_general');
        }

        if($adresseForm->isSubmitted() && $adresseForm->isValid())
        {
            $entityManager->persist($adresseClub);
            $entityManager->flush();
            $this->addFlash('success', 'Adresse du club modifiée !');
            return $this->redirectToRoute('admin_general');
        }

        if($photoForm->isSubmitted() && $photoForm->isValid() && !$photoForm['photo']->isEmpty()) {
            //récupérer fichier + l'envoyer dans le répertoire de destionation
            $photo = $photoForm["photo"]->getData();
            $ext = $photo->getExtension();
            //dd($photo);
            $uploads_directory = $this->getParameter('event_directory'); //dans config/services.yaml
            $fileName = "defaut.jpg";
            $fs = new Filesystem();
            $fs->remove("public/img/photo/evenement/defaut.jpg");
            $photo->move(
                $uploads_directory,
                $fileName
            );
            $this->addFlash('success', 'Photo modifiée !');
            return $this->redirectToRoute('admin_general');
        }

        return $this->render('admin/general.html.twig', [
            "armes" => $armes,
            "adresseClub" => $adresseClub,
            'armeForm' => $armeForm->createView(),
            'adminForm' => $adminForm->createView(),
            'adresseForm' => $adresseForm->createView(),
            'photoForm' => $photoForm->createView()
        ]);
    }


}