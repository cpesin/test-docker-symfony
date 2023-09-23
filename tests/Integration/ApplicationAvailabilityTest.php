<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;

class ApplicationAvailabilityTest extends WebTestCase
{
    use RefreshDatabaseTrait;
    
    /**
     * @var Boolean
     */
    private $debug;
    
    /**
     * @var Array
     */
    private $slugs;

    /**
     * @var Array
     */
    private $excludes;

    /**
     * @var KernelBrowser
     */
    private $client;

    /**
     * @var ContainerInterface
     */
    private $container;
    
    /**
     * @var Router
     */
    private $router;
    
    /**
     * [Description for $databaseTool]
     *
     * @var AbstractDatabaseTool
     */
    private $databaseTool;

    public function setUp(): void
    {
        parent::setUp();
    
        $this->debug = true;

        $this->slugs = ['name' => 'test', 'id' => 1];
        $this->excludes = [];

        $this->client = static::createClient();
        $this->container = self::getContainer();
        $this->router = $this->container->get('router');  
        $this->databaseTool = $this->container->get(DatabaseToolCollection::class)->get();

        $this->databaseTool->loadAliceFixture([
            'fixtures/Repository/AuthorsTestFixtures.yml', 
            'fixtures/Repository/ArticlesTestFixtures.yml'
        ]); 
    }

    public function testPageIsSuccessful(): void
    {
        $collection = $this->router->getRouteCollection();
        $allRoutes = $collection->all();  

        $this->debug('>>> Start test');
        foreach ($allRoutes as $name => $route) {
            if(true === \in_array($name, $this->excludes)) {
                continue;
            }

            $params = $this->getParams($route->getPath());
            $path = $this->router->generate($name, $params);
         
            try {
                $this->debug($name . ' : ' . $path);
                $this->client->request('GET', $path);
                $this->assertTrue($this->client->getResponse()->isSuccessful());
            } catch(\Exception $e) {
                $this->debug('>>> ERROR <<< : ' . $e);
            }        
        }
        $this->debug('<<< End test');
    }

    private function getParams(string $path): array
    {
        $params = [];

        foreach($this->slugs as $slug => $value) {
            if(\strpos($path, '{' . $slug . '}')) {
                $params[$slug] = $value;
            }    
        }

        return $params;
    }

    private function debug(string $data): void
    {
        if(true === $this->debug) {
            dump($data);
        }
    }
    
    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->debug, $this->slugs, $this->excludes, $this->client, $this->container, $this->router);
    }
}
