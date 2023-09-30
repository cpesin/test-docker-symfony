<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;

Class ContactControllerTest extends WebTestCase
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
     * @var KernerBrowser
     */
    private $client;

    /**
     * [Description for $crawler]
     *
     * @var [type]
     */
    private $crawler;

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

        $this->crawler = null;
        $this->client = static::createClient();
        $this->container = self::getContainer();
        $this->databaseTool = $this->container->get(DatabaseToolCollection::class)->get();
        
        $this->databaseTool->loadAliceFixture([
            'fixtures/Repository/AuthorsTestFixtures.yml', 
            'fixtures/Repository/ArticlesTestFixtures.yml'
        ]);
    }
    
    /**
     * [Description for testPage]
     *
     * @return void
     * 
     */
    public function testContactPage() :void
    {
        $this->client->request('GET', '/contact');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Formulaire de contact');
    }

    /**
     * [Description for testSendForm]
     *
     * @return void
     * 
     */
    public function testSendForm(): void
    {
        $this->crawler = $this->client->request('GET', '/contact');
        $submit = $this->crawler->selectButton('Envoyer');
        $form = $submit->form();

        $form['contact[firstname]'] = 'Firstname';
        $form['contact[lastname]'] = 'Lastname';
        $form['contact[email]'] = 'test-email@test.com';
        $form['contact[message]'] = 'Test message';

        $this->client->submit($form);
        $this->assertEmailCount(1);

        $this->client->followRedirect();
        $this->assertSelectorTextContains('div.alert-success', 'Merci pour votre message');
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
        unset($this->container, $this->client, $this->databaseTool, $this->crawler);
    }
}
