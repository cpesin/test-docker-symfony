<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;

Class AuthorsControllerTest extends WebTestCase
{
    /**
     * [Description for $databaseTool]
     *
     * @var AbstractDatabaseTool
     */
    private $databaseTool;

    /**
     * [Description for $client]
     *
     * @var KernelBrowser
     */
    private $client;

    /**
     * [Description for $container]
     *
     * @var ContainerInterface
     */
    private $container;

    /**
     * [Description for setUp]
     *
     * @return void
     * 
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->container = self::getContainer();
        $this->databaseTool = $this->container->get(DatabaseToolCollection::class)->get();
        
        $this->databaseTool->loadAliceFixture([
            'fixtures/Repository/AuthorsTestFixtures.yml', 
            'fixtures/Repository/ArticlesTestFixtures.yml'
        ]);
    }
    
    /**
     * [Description for testAuthorsPage]
     *
     * @return void
     * 
     */
    public function testAuthorsPage() :void
    {
        $this->client->request('GET', '/auteurs');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Liste des auteurs');
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
        unset($this->container, $this->client, $this->databaseTool);
    }
}
