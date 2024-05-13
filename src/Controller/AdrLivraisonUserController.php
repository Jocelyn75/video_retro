<?php

namespace App\Controller;

use App\Entity\AdrLivraisonUser;
use App\Form\AdrLivraisonUserType;
use App\Repository\AdrLivraisonUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/adr/livraison/user')]
class AdrLivraisonUserController extends AbstractController
{
    #[Route('/', name: 'app_adr_livraison_user_index', methods: ['GET'])]
    public function index(AdrLivraisonUserRepository $adrLivraisonUserRepository): Response
    {
        $user = $this->getUser(); // Récupérer l'utilisateur actuellement authentifié

        // Récupérer uniquement les adresses de livraison de l'utilisateur connecté
        $adrLivraisonUsers = $adrLivraisonUserRepository->findByUserId($user->getId());

        return $this->render('adr_livraison_user/index.html.twig', [
            'adr_livraison_users' => $adrLivraisonUsers,
        ]);
    }

    #[Route('/new', name: 'app_adr_livraison_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser(); // Récupérer l'utilisateur actuellement authentifié

        $adrLivraisonUser = new AdrLivraisonUser();
        $adrLivraisonUser->setUser($user); // Associer l'utilisateur à l'adresse de livraison
        $form = $this->createForm(AdrLivraisonUserType::class, $adrLivraisonUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($adrLivraisonUser);
            $entityManager->flush();

            return $this->redirectToRoute('app_adr_livraison_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('adr_livraison_user/new.html.twig', [
            'adr_livraison_user' => $adrLivraisonUser,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_adr_livraison_user_show', methods: ['GET'])]
    public function show(AdrLivraisonUser $adrLivraisonUser): Response
    {
        return $this->render('adr_livraison_user/show.html.twig', [
            'adr_livraison_user' => $adrLivraisonUser,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_adr_livraison_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AdrLivraisonUser $adrLivraisonUser, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdrLivraisonUserType::class, $adrLivraisonUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            
            $this->addFlash('success', 'Votre adresse a bien été modifiée');
            return $this->redirectToRoute('app_adr_livraison_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('adr_livraison_user/edit.html.twig', [
            'adr_livraison_user' => $adrLivraisonUser,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_adr_livraison_user_delete', methods: ['POST'])]
    public function delete(Request $request, AdrLivraisonUser $adrLivraisonUser, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$adrLivraisonUser->getId(), $request->request->get('_token'))) {
            $entityManager->remove($adrLivraisonUser);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_adr_livraison_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
