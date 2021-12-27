<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\PhotoArticle;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DomCrawler\Image;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/gestion/article/create', name: 'article_create')]
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

                //stocker le nom en BDD
                $img = new PhotoArticle();
                $img->setImage($fichier);
                $article->addPhoto($img);

            }

            $article->setAuteur($this->getUser()->getPrenom()." ".$this->getUser()->getNom());
            $article->setDateHeureCreation(new \DateTime());

            $entityManager->persist($article);
            $entityManager->flush();
            $this->addFlash('success', 'Article modifié !');
            return $this->redirectToRoute('main_home');
        }

        return $this->render('article/create.html.twig', [
            'articleForm' => $articleForm->createView()
        ]);
    }

    #[Route('/gestion/article/update/{id}', name: 'article_update')]
    public function update(
        int $id,
        Request $request,
        ArticleRepository $articleRepository,
        EntityManagerInterface $entityManager
    ): Response
    {

        $article = $articleRepository->findOneBy(array('id' => $id));
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

                //stocker le nom en BDD
                $img = new PhotoArticle();
                $img->setImage($fichier);
                $article->addPhoto($img);

            }

            $article->setAuteur($this->getUser()->getPrenom()." ".$this->getUser()->getNom());
            $article->setDateHeureCreation(new \DateTime());

            $entityManager->persist($article);
            $entityManager->flush();
            $this->addFlash('success', 'Article enregistré !');
            return $this->redirectToRoute('main_home');
        }

        return $this->render('article/update.html.twig', [
            'articleForm' => $articleForm->createView(),
            'article' => $article
        ]);
    }

    #[Route('/gestion/article/delete/{id}', name: 'article_delete')]
    public function delete(
        int $id,
        ArticleRepository $articleRepository,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $article = $articleRepository->findOneBy(array('id' => $id));

        foreach ($article->getPhotos() as $photo){
            unlink($this->getParameter('article_directory').'/'.$photo->getImage());
            $entityManager->remove($photo);
            $entityManager->flush();
        }

        $entityManager->remove($article);
        $entityManager->flush();
        $this->addFlash('danger', 'Article supprimé !');
        return $this->redirectToRoute('main_home');
    }

    #[Route('/gestion/article/delete/image/{id}', name: 'article_delete_image', methods: ["DELETE"])]
    public function deleteImage(
        PhotoArticle $photoArticle,
        EntityManagerInterface $entityManager,
        Request $request
    ): JsonResponse
    {

        $data = json_decode($request->getContent(), true);

        //On vérifie si le token est valide
        if($this->isCsrfTokenValid('delete'.$photoArticle->getId(), $data['_token'])){
            //supprimer le fichier
            $nom = $photoArticle->getImage();
            unlink($this->getParameter('article_directory').'/'.$nom);

            //supprimer l'entrée de la base
            $entityManager->remove($photoArticle);
            $entityManager->flush();

            //réponse en json
            return new JsonResponse(['success' => 1]);

        }else{
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }

    }
}
