<?php

declare(strict_types=1);

namespace App\Controller\Admin;

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
    #[Route('/admin/auteurs', name: 'admin_authors')]
    public function index(AuthorRepository $authorRepository): Response
    {
        $authors = $authorRepository->findAll();

        return $this->render('admin/authors/index.html.twig', [
            'authors' => $authors,
        ]);
    }

    /**
     * [Description for edit].
     */
    #[Route('/admin/auteur/{id}', name: 'admin_author_edit')]
    public function edit(AuthorRepository $authorRepository): Response
    {
        $authors = $authorRepository->findAll();

        return $this->render('admin/authors/edit.html.twig', [
            'authors' => $authors,
        ]);
    }
}
