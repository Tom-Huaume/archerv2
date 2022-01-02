<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{
    public int $nbArticles = 15;

    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        //Modifier également le mt_rand dans PhotoArticle
        for($i = 1; $i <= $this->nbArticles; $i++){

            $article = new Article();
            $article->setTitre($faker->text(99));
            $article->setDateHeureCreation($faker->dateTimeBetween('-2 years', 'now'));
            $article->setDescription($faker->text(500));
            $manager->persist($article);
            $this->addReference(Article::class.'_'.$i, $article);

        }

        $manager->flush();
    }
}