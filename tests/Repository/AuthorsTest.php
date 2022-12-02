<?php

namespace App\Tests\Repository;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Faker;

class AuthorsTest extends KernelTestCase
{
    /** @var AbstractDatabaseTool */
    protected $databaseTool;

    protected $container;

    protected $authorRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->container = self::getContainer();
        $this->authorRepository = self::getContainer()->get(AuthorRepository::class);
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function testCount(): void
    {
        $this->databaseTool->loadAliceFixture(['fixtures/Repository/AuthorsTestFixtures.yml']);

        $authors = $this->authorRepository->count([]);
        $this->assertEquals(8, $authors);
    }

    public function testAddAuthor(): void
    {
        $faker = Faker\Factory::create('fr_FR');
        $this->databaseTool->loadAliceFixture(['fixtures/Repository/AuthorsTestFixtures.yml']);

        $author = new Author();
        $author->setFirstname($faker->firstname());
        $author->setLastname($faker->lastname());
        $author->setEmail($faker->email());

        $this->authorRepository->save($author, true);

        $authors = $this->authorRepository->count([]);
        $this->assertEquals(9, $authors);
    }

    public function testDeleteAuthor(): void
    {
        $this->databaseTool->loadAliceFixture(['fixtures/Repository/AuthorsTestFixtures.yml']);

        $authors = $this->authorRepository->findAll();
        $this->authorRepository->remove($authors[0], true);

        $authors = $this->authorRepository->count([]);
        $this->assertEquals(7, $authors);
    }
    
    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }
}
