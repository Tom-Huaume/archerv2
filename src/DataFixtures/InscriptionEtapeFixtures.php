<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Etape;
use App\Entity\InscriptionEtape;
use App\Entity\Membre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class InscriptionEtapeFixtures extends Fixture implements DependentFixtureInterface
{
    private int $nbEvenements = 60;
    private int $nbMembresUser = 100;
    private int $nbEtapesParEvenement = 3;

    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        for($i = 1; $i <= $this->nbEvenements; $i++) {
            for($j = 1; $j <= $this->nbEtapesParEvenement; $j++) {
                //Jeu d'inscriptions validées
                for ($k = 1; $k <= mt_rand(1, 50); $k++) {
                    $inscriptionEtape = new InscriptionEtape();
                    $inscriptionEtape->setValidation(mt_rand(0, 1));
                    $inscriptionEtape->setArme("Arc nu");

                    $dateHeureDebutEtape = $this->getReference(Etape::class .'_'.$i.'_'.$j)->getDateHeureDebut();
                    $inscriptionEtape->setDateHeureInscription($faker->dateTimeBetween('-2 week', $dateHeureDebutEtape));

                    $inscriptionEtape->setEtape($this->getReference(Etape::class .'_'.$i.'_'.$j));
                    $inscriptionEtape->setMembre($this->getReference(Membre::class .'_'.mt_rand(1, $this->nbMembresUser)));
                    $manager->persist($inscriptionEtape);
                }
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            EtapeFixtures::class,
            MembreFixtures::class
        ];
    }
}