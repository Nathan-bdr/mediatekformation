<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Controleur de la page de connexion
 * @author Nathan Boudier
 */
class LoginController extends AbstractController {

    /**
     * Affiche le formulaire de connexion
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response {
        // Récupération éventuelle de l'erreur
        $error = $authenticationUtils->getLastAuthenticationError();
        // Récupération du dernier login utilisé
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * Gère la déconnexion
     */
    #[Route('/logout', name: 'logout')]
    public function logout() {
        
    }
}