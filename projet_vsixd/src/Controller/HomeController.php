<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Vehicule;
use App\Entity\Temoignage;
use App\Entity\Description;
use App\Entity\Horaire;
class HomeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    #[Route('/vehicule', name: 'homepage_user')]
    public function homepage_user(): Response
    {
        $vehicules = $this->entityManager->getRepository(Vehicule::class)->findAll();
        // Utilisez le conteneur pour obtenir le service
        $temoignages = $this->entityManager->getRepository(Temoignage::class)->findAll();
        // Page d'accueil pour les utilisateurs normaux
        $description = $this->entityManager->getRepository(Description::class)->findAll();
        $horaire = $this->entityManager->getRepository(Horaire::class)->findAll();
        return $this->render('homepage_user.html.twig', [
            'vehicules' => $vehicules,
            'temoignages' => $temoignages,
            'description' => $description,
            'horaire' => $horaire,
        ]);
    }
    #[Route('/vehicule', name: 'homepage_admin')]
    public function homepage_admin(): Response
    {
        $vehicule = $this->entityManager->getRepository(Vehicule::class)->findAll();
        $temoignages = $this->entityManager->getRepository(Temoignage::class)->findAll();
        $description = $this->entityManager->getRepository(Description::class)->findAll();
        $horaire = $this->entityManager->getRepository(Horaire::class)->findAll();
        // Page d'accueil pour les administrateurs
        return $this->render('homepage_admin.html.twig', [
            'vehicule' => $vehicule,
            'temoignages' => $temoignages,
            'description' => $description,
            'horaire' => $horaire,
        ]);
    }
}