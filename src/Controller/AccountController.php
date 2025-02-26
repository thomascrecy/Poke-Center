<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\File;

final class AccountController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/account', name: 'app_account')]
    public function index(Request $request): Response
    {
        // Récupérer l'utilisateur connecté
        $userConnected = $this->getUser();

        $userId = $request->query->get('id');
        
        if ($userId) {
            $user = $this->entityManager->getRepository(User::class)->find($userId);

            if (!$user) {
                throw new NotFoundHttpException('Utilisateur non trouvé.');
            }

            return $this->render('account/index.html.twig', [
                'username' => $user->getUsername(),
                'photo' => $user->getProfilePicture(),
                'articles' => $userConnected->getArticles()
            ]);
        }

        // Gestion du formulaire de modification des informations
        $form = $this->createFormBuilder($userConnected)
            ->add('username', TextType::class, [
                'label' => 'Nom d\'utilisateur',
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
            ])
            ->add('sold', IntegerType::class, [
                'label' => 'Solde',
            ])
            ->add('profile_picture', FileType::class, [
                'label' => 'Photo de profil',
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => ['image/png', 'image/jpeg', 'image/jpg'],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide.',
                    ])
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $profilePicture = $form->get('profile_picture')->getData();

            if ($profilePicture) {
                $newFilename = uniqid() . '.' . $profilePicture->guessExtension();
                $profilePicture->move(
                    $this->getParameter('kernel.project_dir') . '/public/uploads/profiles',
                    $newFilename
                );

                $userConnected->setProfilePicture($newFilename);
            }

            $this->entityManager->flush();

            $this->addFlash('success', 'Vos informations ont été mises à jour avec succès.');
            return $this->redirectToRoute('app_account');
        }

        return $this->render('account/index.html.twig', [
            'username' => $userConnected->getUsername(),
            'email' => $userConnected->getEmail(),
            'sold' => $userConnected->getSold(),
            'photo' => $userConnected->getProfilePicture(),
            'articles' => $userConnected->getArticles(),
            'invoices' => $userConnected->getInvoices(),
            'form' => $form->createView(),
        ]);
    }
}
