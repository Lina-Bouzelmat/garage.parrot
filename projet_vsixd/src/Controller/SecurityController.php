<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Vérifie si l'utilisateur est déjà connecté
        if ($this->getUser()) {
            // Récupére les rôles de l'utilisateur connecté
            $roles = $this->getUser()->getRoles();

            // Vérifie si l'utilisateur a le rôle admin
            if (in_array('ROLE_ADMIN', $roles, true)) {
                // Redirige l'utilisateur vers la page d'accueil admin
                return $this->redirectToRoute('homepage_admin');
            } else {
                // Redirige l'utilisateur vers la page d'accueil utilisateur
                return $this->redirectToRoute('homepage_user');
            }
        }

        // Gérer les erreurs de connexion
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}