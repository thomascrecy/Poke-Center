<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EggsController extends AbstractController
{
    #[Route('/eggs', name: 'app_eggs')]
    public function index(): Response
    {
        return $this->render('eggs/index.html.twig', [
            'controller_name' => 'EggsController',
        ]);
    }
}
