<?php

// src/Controller/CartController.php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Article;
use App\Entity\Stock;
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

    #[Route('/cart/validate', name: 'app_cart_validate', methods: ['POST'])]
    public function validateCart(UserInterface $user, EntityManagerInterface $entityManager): Response
    {
        $cart = $user->getCart();

        if (!$cart) {
            $this->addFlash('danger', 'Le panier n\'existe pas.');
            return $this->redirectToRoute('app_cart');
        }

        $totalPrice = 0;
        foreach ($cart->getArticleId() as $article) {
            $totalPrice += $article->getPrice();
        }

        if ($user->getSold() < $totalPrice) {
            $this->addFlash('danger', 'Solde insuffisant pour valider le panier.');
            return $this->redirectToRoute('app_cart');
        }

        // Deduct the total price from the user's balance
        $user->setSold($user->getSold() - $totalPrice);
        $entityManager->persist($user);

        // Update the stock for each article in the cart
        foreach ($cart->getArticleId() as $article) {
            $stock = $entityManager->getRepository(Stock::class)->findOneBy(['article_id' => $article]);

            if ($stock && $stock->getAmount() > 0) {
                $stock->setAmount($stock->getAmount() - 1);
                $entityManager->persist($stock);

                // If stock reaches zero, delete the article and stock
                if ($stock->getAmount() == 0) {
                    // Remove the article from all carts
                    $carts = $entityManager->getRepository(Cart::class)->findAll();
                    foreach ($carts as $otherCart) {
                        if ($otherCart->getArticleId()->contains($article)) {
                            $otherCart->removeArticleId($article);
                            $entityManager->persist($otherCart);
                        }
                    }

                    // Delete the article and stock
                    $entityManager->remove($article);
                    $entityManager->remove($stock);
                }
            } else {
                $this->addFlash('danger', 'Stock insuffisant pour l\'article ' . $article->getName());
                return $this->redirectToRoute('app_cart');
            }
        }

        // Clear the cart
        foreach ($cart->getArticleId() as $article) {
            $cart->removeArticleId($article);
        }
        $entityManager->persist($cart);
        $entityManager->flush();

        $this->addFlash('success', 'Panier validé avec succès.');
        return $this->redirectToRoute('app_cart');
    }
}
