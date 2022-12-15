<?php

namespace App\Tests\Repository;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class ArticlesTest extends KernelTestCase
{    
    use RefreshDatabaseTrait;

    /** @var AbstractDatabaseTool */
    protected $databaseTool;

    protected $container;

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
        $articles = $this->container->get(ArticleRepository::class)->count([]);
        $this->assertEquals(8, $articles);
    }
    
    public function testAddArticle(): void
    {
        $articles = $this->articleRepository->count([]);
        $this->assertEquals(8, $articles);
        
        $authors = $this->authorRepository->findAll();

        $article = new Article();
        $article->setTitle('Article 1');
        $article->setText('Lorem ipsum...');
        $article->setState(true);
        $article->setCreated(new \DateTime('now'));
        $article->setUpdated(new \DateTime('now'));
        $article->setAuthor($authors[0]);

        $this->articleRepository->save($article, true);

        $articles = $this->articleRepository->count([]);
        $this->assertEquals(9, $articles);

        $article = $this->articleRepository->findOneBy(['title' => 'Article 1']);
        $this->assertEquals('Lorem ipsum...', $article->getText());
        $this->assertEquals(true, $article->isState());
        $this->assertEquals(1, $article->getAuthor()->getId());
    }

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

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }
}
