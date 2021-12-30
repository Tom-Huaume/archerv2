<?php

namespace App\DataFixtures;

use App\Entity\Membre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class MembreFixtures extends Fixture
{
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {

    }

    public function load(ObjectManager $manager)
    {

        //membre admin
        for($i = 0; $i < 10; $i++){
            $membre = (new Membre())
                ->setNumLicence()
                ->setNom()
                ->setPrenom()
                ->setDateNaissance()
                ->setTelMobile()
                ->setEmail()
                ->setLateralite()
                ->setStatutLicence()
                //Password = "aaaaaa"
                ->setPassword('$2y$13$zAfnuQj96kOkDkmWLqO6suXF.GkYR2TuzbR3ZLKa5ij4kMddOQYVC')
                ->setSexe()
                ->setCategorieAge()
                ->setTypeLicence()
                ->setRoles();
            $manager->persist($membre);
        }


        //membres secrétaires
        for($i = 0; $i < 10; $i++){
            $membre = (new Membre())
                ->setNumLicence()
                ->setNom()
                ->setPrenom()
                ->setDateNaissance()
                ->setTelMobile()
                ->setEmail()
                ->setLateralite()
                ->setStatutLicence()
                //Password = "aaaaaa"
                ->setPassword('$2y$13$zAfnuQj96kOkDkmWLqO6suXF.GkYR2TuzbR3ZLKa5ij4kMddOQYVC')
                ->setSexe()
                ->setCategorieAge()
                ->setTypeLicence()
                ->setRoles();
            $manager->persist($membre);
        }

        //membres user
        for($i = 0; $i < 10; $i++){
            $membre = (new Membre())
                ->setNumLicence()
                ->setNom()
                ->setPrenom()
                ->setDateNaissance()
                ->setTelMobile()
                ->setEmail()
                ->setLateralite()
                ->setStatutLicence()
                //Password = "aaaaaa"
                ->setPassword('$2y$13$zAfnuQj96kOkDkmWLqO6suXF.GkYR2TuzbR3ZLKa5ij4kMddOQYVC')
                ->setSexe()
                ->setCategorieAge()
                ->setTypeLicence()
                ->setRoles();
                $manager->persist($membre);
        }

        $manager->flush();
    }
}