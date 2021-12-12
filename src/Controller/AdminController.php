<?php

namespace App\Controller;

use App\Entity\Arme;
use App\Form\ArmeType;
use App\Repository\ArmeRepository;
use App\Repository\LieuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    /**
     * @Route("/admin/general", name="admin_general")
     */
    public function general(
        ArmeRepository $armeRepository,
        LieuRepository $lieuRepository,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        //Données pour afficher l'adresse du club et les types d'arme'
        $adresseClub = $lieuRepository->findHomeAddress();
        $armes = $armeRepository->findAll();

        //Création armes
        $arme = new Arme();
        $armeForm = $this->createForm(ArmeType::class, $arme);

        $armeForm->handleRequest($request);

        if($armeForm->isSubmitted() && $armeForm->isValid())
        {
            $entityManager->persist($arme);
            $entityManager->flush();
            $this->addFlash('success', 'Enregistré !');
            return $this->redirectToRoute('admin_general');
        }

        return $this->render('admin/general.html.twig', [
            "armes" => $armes,
            "adresseClub" => $adresseClub,
            'armeForm' => $armeForm->createView()
        ]);
    }


}