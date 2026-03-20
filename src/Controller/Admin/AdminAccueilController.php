<?php
namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controleur admin de l'accueil
 */
class AdminAccueilController extends AbstractController {

    /**
     * Chemin vers la page admin 'accueil'
     */
    private const PAGE_ADMIN_ACCUEIL = "admin/accueil.html.twig";

    #[Route('/admin', name: 'admin.accueil')]
    public function index(): Response {
        return $this->render(self::PAGE_ADMIN_ACCUEIL);
    }
}