<?php

declare(strict_types=1);

namespace App\Controller;

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
    #[Route('/articles', name: 'app_articles')]
    public function index(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findBy(
            ['state' => 1],
            ['created' => 'DESC']
        );

        return $this->render('articles/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/article/{id}', name: 'app_article', requirements: ['id' => '\d+'])]
    public function article(ArticleRepository $articleRepository, int $id): Response
    {
        $article = $articleRepository->findOneById($id);

        return $this->render('articles/article.html.twig', [
            'article' => $article,
        ]);
    }
}
