<?php

declare(strict_types=1);

namespace App\DataFixtures;

use Faker;
use App\Entity\User;
use App\Entity\Author;
use App\Entity\Article;
use App\Entity\Contact;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }
    
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 8; ++$i) {
            $author = new Author();
            $author->setFirstname($faker->firstname());
            $author->setLastname($faker->lastname());
            $author->setEmail($faker->email());

            $manager->persist($author);
        }

        $manager->flush();

        $authors = $manager->getRepository(Author::class)->findAll();

        for ($i = 0; $i < 8; ++$i) {
            $article = new Article();
            $article->setTitle($faker->sentence($faker->numberBetween(3, 6)));
            $article->setText($faker->realText($maxNbChars = 500, $indexSize = 5));
            $article->setState($faker->boolean($chanceOfGettingTrue = 90));
            $article->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeInInterval($startDate = '-2 years', $interval = '+1 year', $timezone = 'Europe/Paris')));
            $article->setUpdatedAt($faker->dateTimeInInterval($startDate = '-6 months', $interval = '+3 months', $timezone = 'Europe/Paris'));

            $article->setAuthor($authors[random_int(0, 7)]);

            $manager->persist($article);
        }

        $manager->flush();

        for ($i = 0; $i < 10; ++$i) {
            $contact = new Contact();
            $contact->setFirstname($faker->firstname());
            $contact->setLastname($faker->lastname());
            $contact->setEmail($faker->email());
            $contact->setMessage($faker->realText($maxNbChars = 200, $indexSize = 5));

            $manager->persist($contact);
        }

        $manager->flush();

        $user = new User();
        $user->setEmail('test@test.com');
        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                '123456'
            )
        );
        $user->setRoles(["ROLE_ADMIN"]);
        $user->setIsVerified(true);

        $manager->persist($user);
        $manager->flush();
    }
}
