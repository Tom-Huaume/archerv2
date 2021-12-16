<?php

namespace App\Controller;

use App\Entity\Membre;
use App\Form\MembreType;
use App\Repository\MembreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class MembreController extends AbstractController
{
    #[Route('/gestion/membre', name: 'membre_list')]
    public function list(
        Request $request,
        EntityManagerInterface $entityManager,
        MembreRepository $membreRepository,
        UserPasswordHasherInterface $passwordHasher
    ): Response
    {
        //liste des lieux
        $membres = $membreRepository->findAll();

        //générer le formulaire de création dans la vue
        $membre = new Membre();
        $membreForm = $this->createForm(MembreType::class, $membre);
        $membreForm->handleRequest($request);

        //traitement du formulaire
        if($membreForm->isSubmitted() && $membreForm->isValid()){

            $membre->setPassword($passwordHasher->hashPassword($membre, 'aaaaaa'));
            $membre->setStatutLicence(1);
            $membre->setRoles(array('ROLE_USER'));
            $entityManager->persist($membre);
            $entityManager->flush();

            $this->addFlash('success', 'Membre enregistré');
            return $this->redirectToRoute('membre_list');
        }

        return $this->render('membre/list.html.twig', [
            'membreForm' => $membreForm->createView(),
            'membres' => $membres,
        ]);
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

    #[Route('/gestion/membre/modifier/{id}', name:'membre_update')]
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
}
