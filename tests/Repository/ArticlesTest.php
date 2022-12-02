<?php

namespace App\Tests\Repository;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Faker;

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
        $faker = Faker\Factory::create('fr_FR');

        $authors = $this->authorRepository->findAll();

        $article = new Article();
        $article->setTitle($faker->sentence($faker->numberBetween(3, 6)));
        $article->setText($faker->realText($maxNbChars = 500, $indexSize = 5));
        $article->setState($faker->boolean($chanceOfGettingTrue = 90));
        $article->setCreated($faker->dateTimeInInterval($startDate = '-2 years', $interval = '+1 year', $timezone = 'Europe/Paris'));
        $article->setUpdated($faker->dateTimeInInterval($startDate = '-6 months', $interval = '+3 months', $timezone = 'Europe/Paris'));
        
        
        $article->setAuthor($authors[random_int(0, 7)]);

        $this->articleRepository->save($article, true);

        $articles = $this->articleRepository->count([]);
        $this->assertEquals(9, $articles);
    }

    public function testDeleteArticle(): void
    {
        $articles = $this->articleRepository->findAll();
        $this->articleRepository->remove($articles[0], true);

        $articles = $this->articleRepository->count([]);
        $this->assertEquals(7, $articles);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }
}
