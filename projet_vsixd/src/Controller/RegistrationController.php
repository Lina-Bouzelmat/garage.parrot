<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{

    public function __construct()
    {
    }
    
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode le mot de passe
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // Persister l'utilisateur en base de données
            $entityManager->persist($user);
            $entityManager->flush();
            
            // Récupérer les rôles de l'utilisateur
            $roles = $user->getRoles();

            // Vérifier si l'utilisateur a le rôle 'ROLE_ADMIN'
            if (in_array('ROLE_ADMIN', $roles, true)) {
                // Rediriger l'utilisateur vers la page d'accueil admin
                return $this->redirectToRoute('homepage_admin');
            } else {
                // Rediriger l'utilisateur vers la page d'accueil utilisateur
                return $this->redirectToRoute('homepage_user');
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}