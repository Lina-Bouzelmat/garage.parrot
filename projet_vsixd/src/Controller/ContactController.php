<?php

namespace App\Controller;

use App\Entity\ContactSubmission;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Horaire;

class ContactController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/contact', name: 'contact')]
    public function contact(Request $request): Response
    {
        // Remplacez ces valeurs factices par vos informations de contact réelles
        $numeros = '+1234567890'; // Numéro de téléphone
        $email = 'contact@example.com'; // Adresse e-mail
        $horaire = $this->entityManager->getRepository(Horaire::class)->findAll();
        $contactSubmission = new ContactSubmission();
        $form = $this->createForm(ContactType::class, $contactSubmission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrez la soumission du formulaire dans la base de données ou traitez-la selon vos besoins
            $this->entityManager->persist($contactSubmission);
            $this->entityManager->flush();

            // Ajoutez un message flash pour confirmer la soumission réussie du formulaire
            $this->addFlash('success', 'Votre message a été envoyé avec succès.');

            // Redirigez l'utilisateur vers une page de confirmation ou une autre page appropriée
            return $this->redirectToRoute('vehicule_list_public');
        }

        return $this->render('contact/contact.html.twig', [
            'Contactform' => $form->createView(),
            'numeros' => $numeros, // Passer le numéro de téléphone au modèle Twig
            'email' => $email, // Passer l'adresse e-mail au modèle Twig
            'horaire' => $horaire,
        ]);
    }
}