<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;

Class contactControllerTest extends WebTestCase
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

        $form['form[name]'] = 'Name';
        $form['form[email]'] = 'test-email@test.com';
        $form['form[message]'] = 'Message de test';

        $this->client->submit($form);
        $this->assertEmailCount(1);

        $this->client->followRedirect();
        $this->assertSelectorTextContains('div.alert-success', 'Merci pour votre message');
    }
}
