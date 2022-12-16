<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SendController extends AbstractController
{
    #[ROUTE('/send', name: 'app_contact_send')]
    public function send(Request $request): Response
    {
        return $this->renderForm('contact/send.html.twig');
    }
}
