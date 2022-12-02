<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Article;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        $author = null;
        for ($i = 0; $i < 8; $i++) {
            $author = new Author();
            $author->setFirstname($faker->firstname());
            $author->setLastname($faker->lastname());
            $author->setEmail($faker->email());

            $manager->persist($author);
        }

        $manager->flush();

        $authors = $manager->getRepository(Author::class)->findAll();

        $articles = Array();
        for ($i = 0; $i < 8; $i++) {
            $article = new Article();
            $article->setTitle($faker->sentence($faker->numberBetween(3, 6)));
            $article->setText($faker->realText($maxNbChars = 500, $indexSize = 5));
            $article->setState($faker->boolean($chanceOfGettingTrue = 90));
            $article->setCreated($faker->dateTimeInInterval($startDate = '-2 years', $interval = '+1 year', $timezone = 'Europe/Paris'));
            $article->setUpdated($faker->dateTimeInInterval($startDate = '-6 months', $interval = '+3 months', $timezone = 'Europe/Paris'));
            
            $article->setAuthor($authors[random_int(0, 7)]);

            $manager->persist($article);
        }

        $manager->flush();
    }
}
