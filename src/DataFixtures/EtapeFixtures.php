<?php

namespace App\DataFixtures;

use App\Entity\Etape;
use App\Entity\Evenement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EtapeFixtures extends Fixture implements DependentFixtureInterface
{
    private int $nbEvenements = 60;

    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        //Entre 1 et 3 étapes pour chaque évènement
        for($i = 1; $i <= $this->nbEvenements; $i++) {
            for ($j = 1; $j <= 3; $j++) {   //!!!Modifier égalementdans Inscription
                $etape = new Etape();
                $etape->setNom("Départ ".$j);
                $etape->setDescription($faker->text(150));
                $dateHeureDebutEvenement = $this->getReference(Evenement::class . '_' .$i)->getDateHeureDebut();
                $etape->setDateHeureDebut($dateHeureDebutEvenement);
                $etape->setDateHeureCreation(new \DateTime());
                $etape->setNbInscriptionsMax(mt_rand(1, 5));
                $this->addReference(Etape::class.'_'.$i.'_'.$j, $etape);

                $etape->setEvenement($this->getReference(Evenement::class . '_' .$i));
                $manager->persist($etape);
            }
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            EvenementFixtures::class
        ];
    }
}