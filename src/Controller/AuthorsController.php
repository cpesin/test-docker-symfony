<?php

namespace App\Controller;

use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * [Description AuthorsController].
 */
class AuthorsController extends AbstractController
{
    /**
     * [Description for index].
     */
    #[Route('/auteurs', name: 'app_authors')]
    public function index(AuthorRepository $authorRepository): Response
    {
        $authors = $authorRepository->findAll();

        return $this->render('authors/index.html.twig', [
            'authors' => $authors,
        ]);
    }
}
