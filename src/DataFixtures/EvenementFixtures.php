<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Evenement;
use App\Entity\Lieu;
use App\Entity\PhotoArticle;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EvenementFixtures extends Fixture implements DependentFixtureInterface
{


    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        for($i = 1; $i <= 60; $i++){    //Modifier également Etape/Trajet/Inscription
            $evenement = new Evenement();
            $evenement->setNom("Concours de ".$faker->city());
            $evenement->setEtat("ouvert");
            $evenement->setDescription($faker->text(200));
            $evenement->setDateHeureDebut($faker->dateTimeBetween('now', '+12 months'));
            $evenement->setDateHeureFin($faker->dateTimeInInterval($evenement->getDateHeureDebut(), '+1 day'));
            $evenement->setDateHeureLimiteInscription($evenement->getDateHeureDebut());
            $evenement->setPhoto('defaut'.mt_rand(1,6).'.jpg');
            $evenement->setDateHeureCreation(new \DateTime());
            $this->addReference(Evenement::class.'_'.$i, $evenement);

            $evenement->setLieuDestination($this->getReference(Lieu::class.'_'.mt_rand(1,25)));
            $manager->persist($evenement);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            LieuFixtures::class
        ];
    }
}