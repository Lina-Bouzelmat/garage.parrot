<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\VehiculeRepository;
use App\Entity\Vehicule;
use App\Entity\Horaire;
use App\Form\VehiType;
use Doctrine\ORM\EntityManagerInterface;

class VehiculeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/vehicule', name: 'vehicule_list_public')]
    public function indexList(): Response
    {
        $numeros = '+1234567890'; // Numéro de téléphone
        $email = 'contact@example.com'; // Adresse e-mail
        $vehicules = $this->entityManager->getRepository(Vehicule::class)->findAll();
        $horaire = $this->entityManager->getRepository(Horaire::class)->findAll();
        return $this->render('vehicule/vehicule.html.twig', [
            'vehicules' => $vehicules,
            'horaire' => $horaire,
            'numeros' => $numeros,
            'email' => $email,
        ]);
    }

    #[Route('/vehicule/{id}', name: 'app_vehicule_show')]
    public function show(int $id): Response
    {
        $vehicule = $this->entityManager->getRepository(Vehicule::class)->find($id);
        
        return $this->render('vehicule/show.html.twig', [
            'vehicule' => $vehicule,
        ]);
    }

    #[Route('/vehicule/edit/{id?}', name: 'app_vehicule_edit')]
    public function edit(Request $request, int $id = null): Response
    {
        $mode = $id ? 'update' : 'new';
        $vehicule = $id ? $this->entityManager->getRepository(Vehicule::class)->find($id) : new Vehicule();

        $form = $this->createForm(VehiType::class, $vehicule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrer le véhicule dans la base de données
            $this->saveVehicule($vehicule, $mode);

            // Redirection vers la page de détails du véhicule
            return $this->redirectToRoute('vehicule_show', ['id' => $vehicule->getId()]);
        }

        return $this->render('vehicule/edit.html.twig', [
            'form' => $form->createView(),
            'vehicule' => $vehicule,
            'mode' => $mode,
        ]);
    }

    #[Route('/vehicule/remove/{id}', name: 'app_vehicule_remove')]
    public function remove(int $id): Response
    {
        $vehicule = $this->entityManager->getRepository(Vehicule::class)->find($id);
        $this->entityManager->remove($vehicule);
        $this->entityManager->flush();

        $this->addFlash('success', 'Véhicule supprimé avec succès.');

        return $this->redirectToRoute('vehicule_list_public');
    }

    public function completeVehiculeBeforeSave(Vehicule $vehicule) {
        if($vehicule->isIsPublished()){
            $vehicule->setPublishedVh(new \DateTime());
        }
        return $vehicule;
    }

    #[Route('/vehicule/add', name: 'app_vehicule_add')]
    public function saveVehicule(Vehicule $vehicule, string $mode){
        $vehicule = $this->completeVehiculeBeforeSave($vehicule, $mode);
        
        $this->entityManager->persist($vehicule);
        $this->entityManager->flush();
        $this->addFlash('success', 'Enregistré avec succès');
    }

    #[Route('/filtre-vehicules', name: 'filtreVehicules')]
    public function filtreVehicules(Request $request, VehiculeRepository $vehiculeRepository): JsonResponse
    {
        // Récupérer les données des filtres depuis la requête AJAX
        $filtres = json_decode($request->getContent(), true);

        // Utiliser les données des filtres pour filtrer les véhicules dans la base de données
        $resultats = $vehiculeRepository->filtrerVehicules($filtres);

        // Retourner les résultats au format JSON
        return new JsonResponse($resultats);
    }
}