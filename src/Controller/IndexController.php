<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(ArticleRepository $articleRepository): Response
    {
        $lastArticle = $articleRepository->findOneBy(
            ['state' => 1],
            ['created' => 'DESC']
        );

        return $this->render('main/index.html.twig', [
            'lastArticle' => $lastArticle,
            'readme' => $this->getReadMe()
        ]);
    }

    private function getReadMe(): string
    {
        $readme = file_get_contents(__DIR__.'/../../README.md');
        $readme = $readme === false ? '' : $readme;
        return $readme;
    }
}
