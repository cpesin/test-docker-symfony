<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Entity\Contact;
use App\Service\Mailer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * [Description MailerTest]
 */
class MailerServiceTest extends KernelTestCase
{
    /**
     * [Description for $container]
     *
     * @var ContainerInterface
     */
    private $container;

    /**
     * [Description for $mailer]
     *
     * @var Mailer
     */
    private $mailer;
    
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
        $this->mailer = $this->container->get(Mailer::class);
    }

    /**
     * [Description for testSendEmail]
     *
     * @return void
     * 
     */
    public function testSendEmail(): void
    {
        $data = new Contact();
        $data->setFirstname('Firstname');
        $data->setLastname('Lastname');
        $data->setEmail('email@test.com');
        $data->setMessage('Test message');

        $this->assertEquals(0, $this->mailer->sendContactEmail($data));
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
        unset($this->container, $this->mailer);
    }
}
