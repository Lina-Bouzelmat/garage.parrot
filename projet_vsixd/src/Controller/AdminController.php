<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Description;
use App\Entity\Horaire;
use App\Form\AdminType;
use App\Form\DescriptionType;
use App\Form\HoraireType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface  $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager , UserPasswordHasherInterface  $passwordHasher )
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/admin/login', name: 'admin_login')]
    public function createAdmin(): Response
    {
        // Créez une nouvelle instance de l'entité Admin
        $admin = new Admin();
        
        $admin->setEmail('linabouzelmat@gmail.com');

        // Encodez le mot de passe
        $encodedPassword = $this->passwordHasher->hashPassword($admin, 'password');
        $admin->setPassword($encodedPassword);

        // Persistez l'entité Admin
        $this->entityManager->persist($admin);
        $this->entityManager->flush();

        // Redirigez l'utilisateur vers la page de connexion ou la page de tableau de bord
        return $this->redirectToRoute('admin_login');
    }

    #[Route('/admin/description/edit', name: 'admin_description_edit')]
    public function editDescription(Request $request): Response
    {
        $description = $this->entityManager->getRepository(Description::class)->findOneBy([]);

        if (!$description) {
            $description = new Description();
        }

        $form = $this->createForm(DescriptionType::class, $description);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($description);
            $this->entityManager->flush();

            $this->addFlash('success', 'Description mise à jour avec succès.');

            return $this->redirectToRoute('admin_dashboard');
        }

        return $this->render('admin/edit_description.html.twig', [
            'descform' => $form->createView(),
        ]);
    }

    #[Route('/admin/horaire/edit', name: 'admin_horaire_edit')]
    public function editHoraire(Request $request): Response
    {
        $horaire = $this->entityManager->getRepository(Horaire::class)->findOneBy([]);

        if (!$horaire) {
            $horaire = new Horaire();
        }

        $form = $this->createForm(HoraireType::class, $horaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($horaire);
            $this->entityManager->flush();

            $this->addFlash('success', 'Horaires mis à jour avec succès.');

            return $this->redirectToRoute('admin_dashboard');
        }

        return $this->render('admin/edit_horaire.html.twig', [
            'horaireform' => $form->createView(),
        ]);
    }

    #[Route('/admin/user/create', name: 'admin_user_create' ,methods:'[GET, POST]')]
    public function createUser(Request $request): Response
    {
        $admin = new Admin();
        $form = $this->createForm(AdminType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $admin->setRoles(['ROLE_ADMIN']);
            $this->entityManager->persist($admin);
            $this->entityManager->flush();

            $this->addFlash('success', 'Utilisateur créé avec succès.');

            return $this->redirectToRoute('admin_dashboard');
        }

        return $this->render('admin/create_user.html.twig', [
            'adminform' => $form->createView(),
        ]);
    }


    #[Route('/admin/user/edit/{id}', name: 'admin_user_edit')]
    public function editUser(Request $request, Admin $admin): Response
    {
        $form = $this->createForm(AdminType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Utilisateur modifié avec succès.');

            return $this->redirectToRoute('admin_dashboard');
        }

        return $this->render('admin/edit_user.html.twig', [
            'adminform' => $form->createView(),
        ]);
    }


    #[Route('/admin/user/delete/{id}', name: 'admin_user_delete')]
    public function deleteUser(Request $request, Admin $admin): Response
    {
        if ($this->isCsrfTokenValid('delete'.$admin->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($admin);
            $this->entityManager->flush();

            $this->addFlash('success', 'Utilisateur supprimé avec succès.');
        }

        return $this->redirectToRoute('admin_dashboard');
    }


    #[Route('/description/add', name: 'app_description_add')]
public function saveDescription(Request $request): Response
{
    $description = new Description();
    // Création du formulaire
    $form = $this->createForm(DescriptionType::class, $description);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Enregistrement de la description dans la base de données
        $this->entityManager->persist($description);
        $this->entityManager->flush();

        $this->addFlash('success', 'Description enregistrée avec succès.');
        return $this->redirectToRoute('admin_dashboard');
    }

    return $this->render('admin/description_add.html.twig', [
        'descform' => $form->createView(),
    ]);
}


    #[Route('/horaire/edit/{id?}', name: 'app_horaire_edit')]
    public function edit_horaire(Request $request, int $id = null): Response
    {
        $mode = $id ? 'update' : 'new';
        $horaire = $id ? $this->entityManager->getRepository(Horaire::class)->find($id) : new Horaire();

        $form = $this->createForm(HoraireType::class, $horaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrer le véhicule dans la base de données
            $this->saveHoraire($horaire, $mode);

            // Redirection vers la page de détails du véhicule
            return $this->redirectToRoute('horaire_show', ['id' => $horaire->getId()]);
        }

        return $this->render('horaire/edit.html.twig', [
            'horaireform' => $form->createView(),
            'horaire' => $horaire,
            'mode' => $mode,
        ]);
    }
    #[Route('/horaire/remove/{id}', name: 'app_horaire_remove')]
    public function remove_horaire(int $id): Response
    {
        $horaire = $this->entityManager->getRepository(Horaire::class)->find($id);
        $this->entityManager->remove($horaire);
        $this->entityManager->flush();

        $this->addFlash('success', 'horaire supprimé avec succès.');

        return $this->redirectToRoute('admin_dashboard');
    }

    #[Route('/description/remove', name: 'app_description_remove')]
public function remove_description(): Response
{
    $description = $this->entityManager->getRepository(Description::class)->findOneBy([]);

    if (!$description) {
        $this->addFlash('error', 'Aucune description à supprimer.');
        return $this->redirectToRoute('admin_dashboard');
    }

    $this->entityManager->remove($description);
    $this->entityManager->flush();

    $this->addFlash('success', 'Description supprimée avec succès.');

    return $this->redirectToRoute('admin_dashboard');
}



    #[Route('/horaire/add', name: 'app_horaire_add')]
    public function saveHoraire(Horaire $horaire){
        $horaire = $this->entityManager->getRepository(Horaire::class);
        
        $this->entityManager->persist($horaire);
        $this->entityManager->flush();
        $this->addFlash('success', 'Enregistré avec succès');
    }

    #[Route('/admin/edit/{id}', name: 'admin_edit')]
    public function edit_admin(Request $request, int $id): Response
    {
        $admin = $this->entityManager->getRepository(Admin::class)->find($id);
        $form = $this->createForm(AdminType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', 'Admin modifié avec succès.');
            return $this->redirectToRoute('admin_dashboard');
        }

        return $this->render('admin/edit_admin.html.twig', [
            'adminform' => $form->createView(),
            'admin' => $admin,
        ]);
    }

    #[Route('/admin/remove/{id}', name: 'admin_remove')]
    public function remove_admin(int $id): Response
    {
        $admin = $this->entityManager->getRepository(Admin::class)->find($id);
        $this->entityManager->remove($admin);
        $this->entityManager->flush();

        $this->addFlash('success', 'Admin supprimé avec succès.');
        return $this->redirectToRoute('admin_dashboard');
    }

    #[Route('/admin/save', name: 'admin_save')]
    public function saveAdmin(Request $request): Response
    {
        $admin = new Admin();
        $form = $this->createForm(AdminType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($admin);
            $this->entityManager->flush();
            $this->addFlash('success', 'Nouvel admin ajouté avec succès.');
            return $this->redirectToRoute('admin_dashboard');
        }

        return $this->render('admin/edit_admin.html.twig', [
            'adminform' => $form->createView(),
            'admin' => $admin,
        ]);
    }
    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    public function dashboard(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

}