<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use App\Repository\ArticleRepository;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;

class AuthorsTest extends KernelTestCase
{
    use RefreshDatabaseTrait;

    /**
     * [Description for $databaseTool]
     *
     * @var AbstractDatabaseTool
     */
    private $databaseTool;

    /**
     * [Description for $container]
     *
     * @var ContainerInterface
     */
    private $container;

    /**
     * [Description for $authorRepository]
     *
     * @var AuthorRepository
     */
    private $authorRepository;

    /**
     * [Description for $articleRepository]
     *
     * @var ArticleRepository
     */
    private $articleRepository;

    /**
     * [Description for setUp]
     *
     * @return void
     * 
     */
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

    /**
     * [Description for testCount]
     *
     * @return void
     * 
     */
    public function testCount(): void
    {
        $authors = $this->authorRepository->count([]);
        $this->assertEquals(8, $authors);
    }

    /**
     * [Description for testAddAuthor]
     *
     * @return void
     * 
     */
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

    /**
     * [Description for testAddArticleToAuthor]
     *
     * @return void
     * 
     */
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

    /**
     * [Description for testRemoveArticleToAuthor]
     *
     * @return void
     * 
     */
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

    /**
     * [Description for testDeleteAuthor]
     *
     * @return void
     * 
     */
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
    
    /**
     * [Description for tearDown]
     *
     * @return void
     * 
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->container, $this->authorRepository, $this->articleRepository, $this->databaseTool);
    }
}
