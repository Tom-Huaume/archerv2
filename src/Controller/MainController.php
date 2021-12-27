<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\EvenementRepository;
use App\Repository\InscriptionEtapeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    /**
     * @Route("/", name="main_home")
     */
    public function home(
        EvenementRepository $evenementRepository,
        ArticleRepository $articleRepository
    ): Response
    {
        $articles = $articleRepository->findAll();

        //$evenements = $evenementRepository->findAll();
        $dateDuJour = new \DateTime('now');
        $evenements = $evenementRepository->findFutureEvents($dateDuJour);

        $concours = [];

        //création d'un tableau d'évènements à injecter dans le calendrier
        foreach ($evenements as $e)
        {
            $concours[] = [
                'id' => $e->getId(),
                'start' => $e->getDateHeureDebut()->format('Y-m-d H:i:s'),
                'end' => $e->getDateHeureFin()->format('Y-m-d H:i:s'),
                'title' => $e->getNom(),
                'description' => $e->getDescription(),
                'backgroundColor' => '#2C3E50',
                'borderColor' => '#2B3C4D',
                'textColor' => '#FFFFFF',
                //'allDay' => false,
            ];
        }

        $dataEvents = json_encode($concours);

        return $this->render('main/home.html.twig', [
            "evenements" => $evenements,
            "concours" => $concours,
            "dataEvents" => $dataEvents,
            "articles" => $articles
        ]);
    }

    #[Route('/gestion/bilan', name:'main_bilan', methods: ["GET", "PUT", "POST"])]
    public function bilan(
        EvenementRepository $evenementRepository,
        EntityManagerInterface $entityManager
    ) : Response
    {
        $dateDuJour = new \DateTime();
        $evenements = $evenementRepository->findPastEvents($dateDuJour);

        return $this->render('main/bilan.html.twig', [
            'evenements' => $evenements
        ]);
    }

}