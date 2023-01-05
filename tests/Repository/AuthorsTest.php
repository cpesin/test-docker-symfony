<?php

namespace App\Tests\Repository;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use App\Repository\ArticleRepository;
use App\Entity\Article;
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
        $this->articleRepository = $this->container->get(ArticleRepository::class);
        $this->databaseTool = $this->container->get(DatabaseToolCollection::class)->get();

        $this->databaseTool->loadAliceFixture([
            'fixtures/Repository/AuthorsTestFixtures.yml', 
            'fixtures/Repository/ArticlesTestFixtures.yml'
        ]);
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
        $this->assertEquals('email1@test.com', $author->getEmail());
    }

    public function testAddArticleToAuthor(): void
    {
        $article = $this->articleRepository->findOneBy(
            ['state' => 1],
            ['created' => 'DESC']
        );
        
        $author = new Author();
        $author->setFirstname('Firstname1');
        $author->setLastname('Lastname1');
        $author->setEmail('email1@test.com');
        $author->addArticle($article);
        
        $this->authorRepository->save($author, true);

        $this->assertCount(1, $author->getArticles());
    }

    public function testRemoveArticleToAuthor(): void
    {
        $article = $this->articleRepository->findOneBy(
            ['state' => 1],
            ['created' => 'DESC']
        );
        
        $author = new Author();
        $author->setFirstname('Firstname1');
        $author->setLastname('Lastname1');
        $author->setEmail('email1@test.com');
        $author->addArticle($article);
        
        $this->authorRepository->save($author, true);

        $author->removeArticle($article);

        $this->assertCount(0, $author->getArticles());
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
