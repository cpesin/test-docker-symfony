<?php

declare(strict_types=1);

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
        return $this->render('authors/index.html.twig', [
            'authors' => $authorRepository->findAll(),
        ]);
    }

    #[Route('/auteur/{id}', name: 'app_author', requirements: ['id' => '\d+'])]
    public function article(AuthorRepository $authorRepository, int $id): Response
    {
        $author = $authorRepository->find($id);

        return $this->render('authors/author.html.twig', [
            'author' => $author,
        ]);
    }
}
