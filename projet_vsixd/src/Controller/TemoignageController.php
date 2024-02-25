<?php

namespace App\Controller;

use App\Entity\Temoignage;
use App\Entity\Horaire;
use App\Form\TemoignageType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class TemoignageController extends AbstractController
{
    private $entityManager;
    private $csrfTokenManager;

    public function __construct(EntityManagerInterface $entityManager, CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->entityManager = $entityManager;
        $this->csrfTokenManager = $csrfTokenManager;
    }

    #[Route('/temoignage/add', name: 'app_temoignage_add')]
public function add(Request $request): Response
{
    $temoignage = new Temoignage();
    $horaire = $this->entityManager->getRepository(Horaire::class)->findAll();
    $form = $this->createForm(TemoignageType::class, $temoignage);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $this->entityManager->persist($temoignage);
        $this->entityManager->flush();

        $this->addFlash('success', 'Témoignage ajouté avec succès.');

        return $this->redirectToRoute('temoignage_list_public');
    }

    return $this->render('temoignage/edit.html.twig', [
        'temform' => $form->createView(),
        'mode' => 'new',
        'horaire' => $horaire,
    ]);
}

    #[Route('/temoignage', name: 'temoignage_list_public')]
    public function indexList(): Response
    {
        $temoignages = $this->entityManager->getRepository(Temoignage::class)->findAll();
        $horaire = $this->entityManager->getRepository(Horaire::class)->findAll();
        return $this->render('temoignage/temoignage.html.twig', [
            'temoignages' => $temoignages,
            'horaire' => $horaire,
        ]);
    }

    #[Route('/temoignage/{id}', name: 'app_temoignage_show')]
    public function show(int $id): Response
    {
        $horaire = $this->entityManager->getRepository(Horaire::class)->findAll();
        $temoignage = $this->entityManager->getRepository(Temoignage::class)->find($id);
        return $this->render('temoignage/show.html.twig', [
            'temoignage' => $temoignage,
            'horaire' => $horaire,
        ]);
    }

    #[Route('/temoignage/edit/{id?}', name: 'app_temoignage_edit')]
    public function edit(Request $request, int $id = null): Response
    {
        $mode = $id ? 'edit' : 'new';
        $temoignage = $id ? $this->entityManager->getRepository(Temoignage::class)->find($id) : new Temoignage();
        $horaire = $this->entityManager->getRepository(Horaire::class)->findAll();
        $form = $this->createForm(TemoignageType::class, $temoignage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->saveTemoignage($temoignage);

            return $this->redirectToRoute('app_temoignage_show', ['id' => $temoignage->getId()]);
        }

        return $this->render('temoignage/edit.html.twig', [
            'temform' => $form->createView(),
            'temoignage' => $temoignage,
            'mode' => $mode,
            'horaire' => $horaire,
        ]);
    }

    #[Route('/temoignage/add', name: 'app_temoignage_add')]
    public function saveTemoignage(Temoignage $temoignage): Response
    {
        $this->entityManager->persist($temoignage);
        $this->entityManager->flush();
        $this->addFlash('success', 'Enregistré avec succès');

        return $this->redirectToRoute('temoignage_list_public');
    }

    #[Route('/temoignage/approve/{id}', name: 'temoignage_approve')]
    public function approve(Request $request, Temoignage $temoignage): Response
    {
        // Vérifiez que l'utilisateur connecté a le rôle d'administrateur
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('Vous n\'avez pas le droit d\'approuver les témoignages.');
        }

        // Vérifiez que la requête est valide et sécurisée avec un jeton CSRF
        if (!$this->isCsrfTokenValid('approve' . $temoignage->getId(), $request->request->get('_token'))) {
            throw new AccessDeniedException('Jeton CSRF non valide.');
        }

        // Approuvez le témoignage ici

        // Ajoutez un message flash pour indiquer que le témoignage a été approuvé avec succès
        $this->addFlash('success', 'Témoignage approuvé avec succès.');

        // Redirigez l'utilisateur vers une page appropriée
        return $this->redirectToRoute('temoignage_list_public');
    }
    #[Route('/temoignage/{id}', name: 'temoignage_delete')]
    public function delete(Request $request, Temoignage $temoignage): Response
    {
        if ($this->isCsrfTokenValid('delete' . $temoignage->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($temoignage);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('temoignage_list_public');
    }
}