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
        for($i = 0; $i < 6; $i++){
            $photoArticle = new PhotoArticle();
            $photoArticle->setImage("https://source.unsplash.com/random/".$i);
            $photoArticle->setArticle($this->getReference(Article::class.'_'.mt_rand(1,3)));
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