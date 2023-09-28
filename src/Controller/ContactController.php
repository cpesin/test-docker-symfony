<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

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
        $form = $this->getform();

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

    /**
     * [Description for getForm].
     */
    private function getForm(): FormInterface
    {
        $form = $this->createFormBuilder()
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Nom',
                ],
                'row_attr' => [
                    'class' => 'form-floating',
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'Email',
                ],
                'row_attr' => [
                    'class' => 'form-floating',
                ],
                'constraints' => [
                    new Email(),
                    new NotBlank(),
                    new Length(['min' => 10]),
                ],
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message',
                'attr' => [
                    'placeholder' => 'Message',
                ],
                'row_attr' => [
                    'class' => 'form-floating',
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 10]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer',
            ])
            ->getForm();

        return $form;
    }
}
