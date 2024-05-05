<?php

namespace App\Controller;

use App\Form\ChangePasswordFormType;
use App\Form\ProfilType;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    private EntityManagerInterface $entityManager; // Déclarez l'attribut

    public function __construct(EntityManagerInterface $entityManager) // Injectez EntityManagerInterface
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/profil', name: 'app_profil')]    
    /**
     * index
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('profil/index.html.twig', []);
    }

    #[Route('/profil/edit', name: 'app_profil_edit', methods: ['GET', 'POST'])]    
    /**
     * edit
     *
     * @param  mixed $request
     * @param  mixed $em
     * @return Response
     */
    public function edit(Request $request, EntityManagerInterface $em): Response
    {
        // Récupération de l'utilisateur actuellement connecté
        $user = $this->getUser();

        // Création du formulaire
        $form = $this->createForm(ProfilType::class, $user);

        // Gestion de la soumission du formulaire
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // Mise à jour des informations de l'utilisateur
            // ...

            // Sauvegarde des changements dans la base de données
            $em->persist($user);
            $em->flush();

            // Redirection vers la page de profil avec un message de confirmation
            $this->addFlash('success', 'Vos modifications ont été enregistrées.');
            return $this->redirectToRoute('app_profil');
        }

        return $this->render('profil/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // #[Route('/profil/modifier-mot-de-passe', name: 'profil_modifier_mot_de_passe')]
    // public function modifierMotDePasse(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    // {
    
    //     // Récupération de l'utilisateur actuellement connecté
    //     $user = $this->getUser();

    //     // Création du formulaire de modification de mot de passe
    //     $form = $this->createForm(ChangePasswordFormType::class);
    //     $form->handleRequest($request);
    
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         // Récupération des données du formulaire
    //         $formData = $form->getData();
    
    //         // Hashage du nouveau mot de passe
    //         $newPassword = $passwordHasher->hashPassword($user, $formData['plainPassword']);
            
    //         // Mise à jour du mot de passe de l'utilisateur
    //         $user->setPassword($newPassword);
    
    //         // Enregistrement des changements dans la base de données
    //         $this->entityManager->flush(); // Utilisez l'entityManagerInterface pour effectuer l'opération
    
    //         // Redirection vers la page de profil avec un message de confirmation
    //         $this->addFlash('success', 'Votre mot de passe a été modifié avec succès.');
    //         return $this->redirectToRoute('app_profil');
    //     }
    
    //     return $this->render('profil/modifier_mot_de_passe.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    // }
    
}


