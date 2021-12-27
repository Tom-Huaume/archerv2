<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/gestion/article', name: 'article_create')]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {

        $article = new Article();
        $articleForm = $this->createForm(ArticleType::class, $article);
        $articleForm->handleRequest($request);

        if($articleForm->isSubmitted() && $articleForm->isValid())
        {
            //récupération des images
            $photos = $articleForm->get('photos')->getData();
            foreach ($photos as $photo){
                //nouveau nom de fichier
                $fichier = md5(uniqid()).'.'.$photo->guessExtension();

                //copie dans le fichier upload
                $photo->move(
                    $this->getParameter('article_directory'),
                    $fichier
                );

                //stocker le nom

            }

            $article->setAuteur($this->getUser()->getPrenom()." ".$this->getUser()->getNom());
            $article->setDateHeureCreation(new \DateTime());

            $entityManager->persist($article);
            $entityManager->flush();
            $this->addFlash('success', 'Article enregistré !');
            return $this->redirectToRoute('main_home');
        }

        return $this->render('article/create.html.twig', [
            'articleForm' => $articleForm->createView()
        ]);
    }
}
