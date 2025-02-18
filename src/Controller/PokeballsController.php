<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PokeballsController extends AbstractController
{
    #[Route('/balls', name: 'app_pokeballs')]
    public function index(): Response
    {
        return $this->render('pokeballs/index.html.twig', [
            'controller_name' => 'PokeballsController',
        ]);
    }
}
