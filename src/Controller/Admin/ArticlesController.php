<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        $articles = $articleRepository->findAll();

        return $this->render('admin/articles/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * [Description for edit].
     */
    #[Route('/admin/article/{id}', name: 'admin_article_edit')]
    public function edit(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAll();

        return $this->render('admin/articles/edit.html.twig', [
            'articles' => $articles,
        ]);
    }
}
