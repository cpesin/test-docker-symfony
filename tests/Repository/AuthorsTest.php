<?php

namespace App\Tests\Repository;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class AuthorsTest extends KernelTestCase
{
    use RefreshDatabaseTrait;

    /** @var AbstractDatabaseTool */
    protected $databaseTool;

    protected $container;

    protected $authorRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->container = self::getContainer();
        $this->authorRepository = $this->container->get(AuthorRepository::class);
        $this->databaseTool = $this->container->get(DatabaseToolCollection::class)->get();

        $this->databaseTool->loadAliceFixture(['fixtures/Repository/AuthorsTestFixtures.yml']);
    }

    public function testCount(): void
    {
        $authors = $this->authorRepository->count([]);
        $this->assertEquals(8, $authors);
    }

    public function testAddAuthor(): void
    {
        $authors = $this->authorRepository->count([]);
        $this->assertEquals(8, $authors);

        $author = new Author();
        $author->setFirstname('Firstname1');
        $author->setLastname('Lastname1');
        $author->setEmail('email1@test.com');

        $this->authorRepository->save($author, true);

        $authors = $this->authorRepository->count([]);
        $this->assertEquals(9, $authors);

        $author = $this->authorRepository->findOneBy(['email' => 'email1@test.com']);
        $this->assertEquals('Firstname1', $author->getFirstname());
        $this->assertEquals('Lastname1', $author->getLastname());
    }

    public function testDeleteAuthor(): void
    {
        $authors = $this->authorRepository->count([]);
        $this->assertEquals(8, $authors);

        $authors = $this->authorRepository->findAll();

        $remove_author = $authors[0];
        $this->authorRepository->remove($remove_author, true);

        $authors = $this->authorRepository->count([]);
        $this->assertEquals(7, $authors);
        
        $author = $this->authorRepository->findOneBy(['id' => $remove_author->getId()]);
        $this->assertEquals(0, $author);
    }
    
    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }
}
