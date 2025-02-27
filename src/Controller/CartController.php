<?php

namespace App\Controller;

// src/Controller/CartController.php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

final class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(UserInterface $user): Response
    {
        $cart = $user->getCart();

        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
            'cart' => $cart,
            'articles' => $cart ? $cart->getArticleId() : [],
        ]);
    }

    #[Route('/cart/add/{id}', name: 'app_cart_add', methods: ['POST'])]
    public function addToCart(int $id, UserInterface $user, EntityManagerInterface $entityManager): Response
    {
        $cart = $user->getCart();

        if (!$cart) {
            $cart = new Cart();
            $cart->setAuthorId($user);
            $entityManager->persist($cart);
        }

        $article = $entityManager->getRepository(Article::class)->find($id);

        if (!$article) {
            $this->addFlash('danger', 'L\'article n\'existe pas.');
            return $this->redirectToRoute('app_cart');
        }

        $cart->addArticleId($article);
        $entityManager->persist($cart);
        $entityManager->flush();

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/remove/{id}', name: 'app_cart_remove', methods: ['POST'])]
    public function removeFromCart(int $id, UserInterface $user, EntityManagerInterface $entityManager): Response
    {
        $cart = $user->getCart();

        if (!$cart) {
            $this->addFlash('danger', 'Le panier n\'existe pas.');
            return $this->redirectToRoute('app_cart');
        }

        $article = $entityManager->getRepository(Article::class)->find($id);

        if (!$article) {
            $this->addFlash('danger', 'L\'article n\'existe pas.');
            return $this->redirectToRoute('app_cart');
        }

        $cart->removeArticleId($article);
        $entityManager->persist($cart);
        $entityManager->flush();

        return $this->redirectToRoute('app_cart');
    }
}

