<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Article;
use App\Entity\Invoice;
use App\Entity\Cart;
use App\Entity\Stock;
use App\Entity\Category;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Admin Panel');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),
            MenuItem::linkToCrud('Utilisateurs', 'fa fa-users', User::class),
            MenuItem::linkToCrud('Catégories', 'fa fa-list', Category::class),
            MenuItem::linkToCrud('Articles', 'fa fa-cookie-bite', Article::class),
            MenuItem::linkToCrud('Factures', 'fa fa-receipt', Invoice::class),
            MenuItem::linkToCrud('Paniers', 'fa fa-cart-shopping', Cart::class),
            MenuItem::linkToCrud('Stocks', 'fa fa-boxes-stacked', Stock::class),
        ];
    }
}
