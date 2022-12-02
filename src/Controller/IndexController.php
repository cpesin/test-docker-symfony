<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Repository\ArticleRepository;

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