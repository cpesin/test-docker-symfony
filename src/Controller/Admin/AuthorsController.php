<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Author;
use App\Form\Admin\AuthorType;
use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    #[Route('/admin/auteurs', name: 'admin_authors', methods: ['GET'])]
    public function index(AuthorRepository $authorRepository): Response
    {
        return $this->render('admin/authors/index.html.twig', [
            'authors' => $authorRepository->findAll(),
        ]);
    }

    /**
     * [Description for new].
     */
    #[Route('/admin/auteurs/nouveau', name: 'admin_authors_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AuthorRepository $authorRepository): Response
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $authorRepository->save($author, true);

            return $this->redirectToRoute('admin_authors', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/authors/new.html.twig', [
            'author' => $author,
            'form' => $form,
        ]);
    }

    /**
     * [Description for edit].
     */
    #[Route('/admin/auteurs/{id}', name: 'admin_authors_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Author $author, AuthorRepository $authorRepository): Response
    {
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $authorRepository->save($author, true);

            return $this->redirectToRoute('admin_authors', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/authors/edit.html.twig', [
            'author' => $author,
            'form' => $form,
        ]);
    }

    /**
     * [Description for delete].
     */
    #[Route('/admin/authors/supprimer/{id}', name: 'admin_authors_delete', methods: ['POST'])]
    public function delete(Request $request, Author $author, AuthorRepository $authorRepository): Response
    {
        /** @var string|null */
        $token = $request->request->get('_token');

        if ($this->isCsrfTokenValid('delete'.$author->getId(), $token)) {
            $authorRepository->remove($author, true);
        }

        return $this->redirectToRoute('admin_authors', [], Response::HTTP_SEE_OTHER);
    }
}
