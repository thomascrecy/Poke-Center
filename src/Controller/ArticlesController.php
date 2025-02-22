<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticlesController extends AbstractController
{
    #[Route('/articles', name: 'get_articles', methods: ['GET'])]
    public function getAllArticles(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categoryId = $request->query->get('category');

        $articleRepository = $entityManager->getRepository(Article::class);
        $categoryRepository = $entityManager->getRepository(Category::class);

        if ($categoryId) {
            $category = $categoryRepository->find($categoryId);

            if (!$category) {
                throw $this->createNotFoundException('Catégorie non trouvée');
            }

            $articles = $category->getArticles();
        } else {
            $articles = $articleRepository->findAll();
        }

        return $this->render('articles/index.html.twig', [
            'articles' => $articles,
        ]);
    }
}
