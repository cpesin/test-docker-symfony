<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Contact;
use App\Service\Mailer;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * [Description ContactController].
 */
class ContactController extends AbstractController
{
    /**
     * [Description for index].
     */
    #[Route('/contact', name: 'app_contact', methods: ['GET', 'POST'])]
    public function index(Request $request, Mailer $mailer): Response
    {
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $mailer->sendEmail($data);

            return $this->redirectToRoute('app_contact_send');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * [Description for send].
     */
    #[ROUTE('/contact/send', name: 'app_contact_send', methods: ['GET'])]
    public function send(Request $request): Response
    {
        return $this->render('contact/send.html.twig');
    }
}
