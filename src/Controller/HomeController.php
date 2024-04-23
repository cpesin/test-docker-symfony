<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository): Response
    {
        $lastArticle = $articleRepository->findOneBy(
            ['state' => 1],
            ['createdAt' => 'DESC']
        );

        return $this->render('home/index.html.twig', [
            'lastArticle' => $lastArticle,
            'readme' => $this->getReadMe(),
        ]);
    }

    private function getReadMe(): string
    {
        $readme = file_get_contents(__DIR__.'/../../README.md');

        return false !== $readme ? $readme : '';
    }
}
