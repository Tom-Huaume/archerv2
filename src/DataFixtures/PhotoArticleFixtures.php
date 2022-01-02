<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\PhotoArticle;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PhotoArticleFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');


        for($i = 1; $i <= 6; $i++){
            $photoArticle = new PhotoArticle();
            $photoArticle->setImage('defaut'.$i.'.jpg');
            $photoArticle->setArticle($this->getReference(Article::class.'_'.mt_rand(1,15)));
            $manager->persist($photoArticle);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ArticleFixtures::class
        ];
    }
}