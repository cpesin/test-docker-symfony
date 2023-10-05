<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use App\Service\Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact', methods: ['GET', 'POST'])]
    public function index(Request $request, Mailer $mailer, ContactRepository $repository): Response
    {
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $repository->save($data, true);

            $mailer->sendContactEmail($data);

            return $this->redirectToRoute('app_contact_send');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form,
        ]);
    }

    #[ROUTE('/contact/send', name: 'app_contact_send', methods: ['GET'])]
    public function send(Request $request): Response
    {
        return $this->render('contact/send.html.twig');
    }
}
