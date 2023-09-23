<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;

Class IndexControllerTest extends WebTestCase
{ 
    use RefreshDatabaseTrait;

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
     * [Description for testHomePage]
     *
     * @return void
     * 
     */
    public function testHomePage() :void
    {
        $this->client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('div.card-header', 'Dernier article');
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
