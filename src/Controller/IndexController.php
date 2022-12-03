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
        ]);
    }
}
