<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * [Description IndexController].
 */
class IndexController extends AbstractController
{
    /**
     * [Description for index].
     */
    #[Route('/admin', name: 'admin_index')]
    public function index(): Response
    {
        return $this->render('admin/main/index.html.twig');
    }
}
