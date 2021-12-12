<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Form\LieuType;
use App\Repository\LieuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LieuController extends AbstractController
{
    #[Route('/admin/lieux', name: 'lieu_create')]
    public function create(
        Request $request,
        LieuRepository $lieuRepository,
        EntityManagerInterface $entityManager
    ): Response
    {
        //afficher la liste des lieux
        $lieux = $lieuRepository->findAll();

        //générer le formulaire de création dans la vue
        $lieu = new Lieu();
        $lieuForm = $this->createForm(LieuType::class, $lieu);

        $lieuForm->handleRequest($request);

        //traitement du formulaire
        if($lieuForm->isSubmitted() && $lieuForm->isValid()){

            $lieu->setClub(0);
            $lieu->setList(1);
            $entityManager->persist($lieu);
            $entityManager->flush();

            $this->addFlash('success', 'Lieu engristré');
            return $this->redirectToRoute('lieu_create');
        }

        return $this->render('lieu/create.html.twig', [
            'lieuForm' => $lieuForm->createView(),
            'listLieux' => $lieux
        ]);
    }

    #[Route('/admin/lieu/delete/{id}', name: 'lieu_delete')]
    public function delete(
        Lieu $lieu,
        EntityManagerInterface $entityManager
    ): Response
    {
        //Supprime l'arme
        $entityManager->remove($lieu);
        $entityManager->flush();

        $this->addFlash('danger', 'Lieu supprimé');
        return $this->redirectToRoute('lieu_create');
    }
}
