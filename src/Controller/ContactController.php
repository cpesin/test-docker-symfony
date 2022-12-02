<?php

namespace App\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email as MimeEmail;

class ContactController extends AbstractController
{
    private $data;

    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {

        $form = $this->getform();
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $this->data = $form->getData();

            $send = $this->sendEmail($mailer);

            return $this->redirectToRoute('app_contact_send');
        }

        return $this->renderForm('contact/index.html.twig', [
            'form' => $form
        ]);
    }

    private function getForm()
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
                ]
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
                ]
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
                ]
            ])
            ->add('submit', SubmitType::class)
            ->getForm();
        
        return $form;
    }

    private function sendEmail(MailerInterface $mailer): void
    {
        $email = (new MimeEmail())
            ->from('hello@example.com')
            ->to('christophe.pesin@niji.fr')
            ->subject('[test] Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>name: '.$this->data['name'].'</p><p>email: '.$this->data['email'].'</p><p>message: '.$this->data['message'].'</p>');

        try{
            $mailer->send($email);
        } 
        catch(Exception $e)
        {
            echo $e;
            exit;
        }
    }
}
