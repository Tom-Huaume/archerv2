<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Lieu;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LieuFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        $lieu = new Lieu();
        $lieu->setNom("Club de Liffré");
        $lieu->setRue($faker->streetAddress());
        $lieu->setRue2($faker->secondaryAddress());
        $lieu->setVille($faker->city());
        $lieu->setCodePostal($faker->postcode());
        $lieu->setClub(1);
        $lieu->setList(1);
        $manager->persist($lieu);
        $this->addReference(Lieu::class.'_1', $lieu);

        for($i = 2; $i <= 25; $i++){

            $lieu = new Lieu();
            $lieu->setNom("Club de ".$faker->city());
            $lieu->setRue($faker->streetAddress());
            $lieu->setRue2($faker->secondaryAddress());
            $lieu->setVille($faker->city());
            $lieu->setCodePostal($faker->postcode());
            $lieu->setClub(0);
            $lieu->setList(1);
            $this->addReference(Lieu::class.'_'.$i, $lieu);

            $manager->persist($lieu);

        }

        $manager->flush();
    }
}
