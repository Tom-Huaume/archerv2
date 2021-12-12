<?php

namespace App\Controller;

use App\Entity\Arme;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArmeController extends AbstractController
{

    #[Route('/arme/admin/delete/{id}', name: 'arme_delete')]
    public function delete(Arme $arme, EntityManagerInterface $entityManager): Response
    {
        //Supprime l'arme
        $entityManager->remove($arme);
        $entityManager->flush();

        return $this->redirectToRoute('admin_general');
    }
}
