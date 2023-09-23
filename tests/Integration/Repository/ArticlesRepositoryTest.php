<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Entity\Article;
use App\Repository\AuthorRepository;
use App\Repository\ArticleRepository;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;

class ArticlesRepositoryTest extends KernelTestCase
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
        $articles = $this->container->get(ArticleRepository::class)->count([]);
        $this->assertEquals(8, $articles);
    }
    
    /**
     * [Description for testAddArticle]
     *
     * @return void
     * 
     */
    public function testAddArticle(): void
    {
        $articles = $this->articleRepository->count([]);
        $this->assertEquals(8, $articles);
        
        $authors = $this->authorRepository->findAll();

        $date = new \DateTime('now');

        $article = new Article();
        $article->setTitle('Article 1');
        $article->setText('Lorem ipsum...');
        $article->setState(true);
        $article->setCreated($date);
        $article->setUpdated($date);
        $article->setAuthor($authors[0]);

        $this->articleRepository->save($article, true);

        $articles = $this->articleRepository->count([]);
        $this->assertEquals(9, $articles);

        $article = $this->articleRepository->findOneBy(['title' => 'Article 1']);
        $this->assertEquals('Article 1', $article->getTitle());
        $this->assertEquals('Lorem ipsum...', $article->getText());
        $this->assertEquals(true, $article->isState());
        $this->assertEquals(1, $article->getAuthor()->getId());
        $this->assertEquals($date, $article->getCreated());
        $this->assertEquals($date, $article->getUpdated());
    }

    /**
     * [Description for testDeleteArticle]
     *
     * @return void
     * 
     */
    public function testDeleteArticle(): void
    {
        $articles = $this->articleRepository->count([]);
        $this->assertEquals(8, $articles);

        $articles = $this->articleRepository->findAll();

        $remove_article = $articles[0];
        $this->articleRepository->remove($remove_article, true);

        $articles = $this->articleRepository->count([]);
        $this->assertEquals(7, $articles);

        $article = $this->articleRepository->findOneBy(['id' => $remove_article->getId()]);
        $this->assertEquals(0, $article);
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
