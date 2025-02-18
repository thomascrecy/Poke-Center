<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PokeCenterController extends AbstractController
{
    #[Route('/', name: 'app_poke_center')]
    public function index(): Response
    {
        return $this->render('poke_center/index.html.twig', [
            'controller_name' => 'PokeCenterController',
        ]);
    }
}
