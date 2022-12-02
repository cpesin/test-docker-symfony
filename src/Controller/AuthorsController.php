<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\AuthorRepository;

class AuthorsController extends AbstractController
{
    #[Route('/auteurs', name: 'app_authors')]
    public function index(AuthorRepository $authorRepository): Response
    {
        $authors = $authorRepository->findAll();

        return $this->render('authors/index.html.twig', [
            'authors' => $authors,
        ]);
    }
}
