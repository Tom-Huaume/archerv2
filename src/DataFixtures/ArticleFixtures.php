<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i = 1; $i <= 3; $i++){

            $article = new Article();
            $article->setTitre("Lorem titre ".$i);
            $article->setDateHeureCreation(new \DateTime());
            $article->setDescription("
                Auctor officia aliquip, quidem litora, deleniti sit! Possimus, 
                nostra. Auctor, eleifend! Adipiscing nisl curabitur, ipsa do! 
                Eveniet, illum do torquent, ipsam condimentum porro vero. Sem a 
                varius congue libero veritatis fermentum sociosqu commodi animi 
                consectetuer eleifend posuere varius iste, iusto sodales class, 
                anim ullam? Per rhoncus blanditiis parturient tempore in wisi 
                fermentum? Provident atque, dis. Wisi earum accumsan elit ab, 
                cubilia lacus modi natus mollis.
            ");
            $manager->persist($article);
            $this->addReference(Article::class.'_'.$i, $article);

        }

        $manager->flush();
    }
}