<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\Admin\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * [Description ArticlesController].
 */
class ArticlesController extends AbstractController
{
    /**
     * [Description for index].
     */
    #[Route('/admin/articles', name: 'admin_articles')]
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('admin/articles/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    /**
     * [Description for new].
     */
    #[Route('/admin/articles/nouveau', name: 'admin_articles_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ArticleRepository $articleRepository): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $articleRepository->save($article, true);

            return $this->redirectToRoute('admin_articles', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/articles/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    /**
     * [Description for edit].
     */
    #[Route('/admin/articles/{id}', name: 'admin_articles_edit')]
    public function edit(Request $request, Article $article, ArticleRepository $articleRepository): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $articleRepository->save($article, true);

            return $this->redirectToRoute('admin_articles', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/articles/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    /**
     * [Description for delete].
     */
    #[Route('/admin/articles/supprimer/{id}', name: 'admin_articles_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, ArticleRepository $articleRepository): Response
    {
        /** @var string|null */
        $token = $request->request->get('_token');

        if ($this->isCsrfTokenValid('delete'.$article->getId(), $token)) {
            $articleRepository->remove($article, true);
        }

        return $this->redirectToRoute('admin_articles', [], Response::HTTP_SEE_OTHER);
    }
}
