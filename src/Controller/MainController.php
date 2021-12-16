<?php

namespace App\Controller;

use App\Repository\EvenementRepository;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    /**
     * @Route("/", name="main_home")
     */
    public function home(EvenementRepository $evenementRepository): \Symfony\Component\HttpFoundation\Response
    {
        $evenements = $evenementRepository->findAll();

        $concours = [];

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
        ]);
    }

}