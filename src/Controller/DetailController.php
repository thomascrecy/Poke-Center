<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\SellType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

final class DetailController extends AbstractController
{
    #[Route('/detail/{id}', name: 'app_detail')]
    public function index(int $id, EntityManagerInterface $em): Response
    {
        // Récupérer l'article par son ID
        $article = $em->getRepository(Article::class)->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Article non trouvé');
        }

        $user = $this->getUser();
        $canEdit = $user && $user->getId() === $article->getAuthorId()->getId();

        $stock = $article->getStock();
        $stock = $stock ? $stock->getAmount() : 0;

        return $this->render('detail/index.html.twig', [
            'article' => $article,
            'stock' => $stock,
            'can_edit' => $canEdit, // Transmettre la variable pour l'affichage dans la vue
        ]);
    }

    #[Route('/edit/{id}', name: 'app_edit')]
    public function edit(int $id, EntityManagerInterface $em, Request $request, SluggerInterface $slugger): Response
    {
        $article = $em->getRepository(Article::class)->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Article non trouvé');
        }

        // Vérifier que l'utilisateur connecté est l'auteur de l'article
        $user = $this->getUser();  // Utilisation de getUser() pour récupérer l'utilisateur
        if ($user->getId() !== $article->getAuthorId()->getId()) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas l\'auteur de cet article');
        }

        // Créer et gérer le formulaire
        $form = $this->createForm(SellType::class, $article);

        // Traiter la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();
            
            if ($imageFile) {
                // Générer un nom unique pour l'image
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                // Déplacer l'image vers le répertoire de destination
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'), // Assurez-vous que ce paramètre est bien défini dans services.yaml
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Gérer l'erreur si le déplacement de l'image échoue
                    $this->addFlash('error', 'Une erreur est survenue lors du téléchargement de l\'image');
                    return $this->redirectToRoute('app_edit', ['id' => $article->getId()]);
                }

                // Mettre à jour le champ image_link de l'article
                $article->setImageLink($newFilename);
            }

            // Enregistrer les changements en base de données
            $em->flush();

            $this->addFlash('success', 'L\'article a été modifié avec succès');
            return $this->redirectToRoute('app_detail', ['id' => $article->getId()]);
        }

        return $this->render('detail/edit.html.twig', [
            'form' => $form->createView(),
            'article' => $article,
        ]);
    }
}
