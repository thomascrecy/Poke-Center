<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticlesController extends AbstractController
{
    #[Route('/articles', name: 'get_articles', methods: ['GET'])]
    public function getAllArticles(EntityManagerInterface $entityManager): Response
    {
        // Récupérer le repository de l'entité Article
        $articleRepository = $entityManager->getRepository(Article::class);

        // Récupérer tous les articles
        $articles = $articleRepository->findAll();

        // Retourner les articles dans un template Twig
        return $this->render('articles/index.html.twig', [
            'articles' => $articles,
        ]);
    }
}
