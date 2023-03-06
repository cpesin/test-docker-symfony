<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * [Description SendController].
 */
class SendController extends AbstractController
{
    /**
     * [Description for send].
     */
    #[ROUTE('/send', name: 'app_contact_send')]
    public function send(Request $request): Response
    {
        return $this->render('contact/send.html.twig');
    }
}
