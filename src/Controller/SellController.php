<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Stock;
use App\Form\SellType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class SellController extends AbstractController
{
    #[Route('/sell', name: 'app_sell')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): Response {
        $newArticle = new Article();
        $form = $this->createForm(SellType::class, $newArticle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new \Exception('Erreur lors de l\'upload de l\'image.');
                }

                $newArticle->setImageLink($newFilename);
            }

            $newArticle->setAuthorId($this->getUser());
            $newArticle->setCreatedAt(new \DateTimeImmutable());

            $stock = new Stock();
            $stock->setAmount($form->get('amount')->getData());
            $stock->setArticleId($newArticle);

            $entityManager->persist($newArticle);
            $entityManager->persist($stock);
            $entityManager->flush();

            return $this->redirectToRoute('app_account');
        }

        return $this->render('sell/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
