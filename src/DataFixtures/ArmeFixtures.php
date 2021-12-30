<?php

namespace App\DataFixtures;

use App\Entity\Arme;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArmeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i = 1; $i <= 8; $i++){
            $arme = new Arme();
            $arme->setSigle("A".$i);
            $arme->setDesignation("Arme ".$i);
            $manager->persist($arme);
        }

        $manager->flush();
    }
}