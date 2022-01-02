<?php

namespace App\DataFixtures;

use App\Entity\Arme;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArmeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $arme = new Arme();
        $arme->setSigle("AC");
        $arme->setDesignation("Arc classique");
        $manager->persist($arme);
        $manager->flush();

        $arme2 = new Arme();
        $arme2->setSigle("BB");
        $arme2->setDesignation("Arc nu");
        $manager->persist($arme2);
        $manager->flush();

        $arme3 = new Arme();
        $arme3->setSigle("CO");
        $arme3->setDesignation("Arc à Poulies");
        $manager->persist($arme3);
        $manager->flush();

        $arme4 = new Arme();
        $arme4->setSigle("CH");
        $arme4->setDesignation("Arc Chasse");
        $manager->persist($arme4);
        $manager->flush();

        $arme5 = new Arme();
        $arme5->setSigle("AD");
        $arme5->setDesignation("Arc Droit");
        $manager->persist($arme5);
        $manager->flush();

        $arme6 = new Arme();
        $arme6->setSigle("AL");
        $arme6->setDesignation("Arc Libre");
        $manager->persist($arme6);
        $manager->flush();

    }
}