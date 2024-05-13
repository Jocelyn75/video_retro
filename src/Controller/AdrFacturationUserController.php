<?php

namespace App\Controller;

use App\Entity\AdrFacturationUser;
use App\Form\AdrFacturationUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AdrFacturationUserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/adr/facturation/user')]
class AdrFacturationUserController extends AbstractController
{
    #[Route('/', name: 'app_adr_facturation_user_index', methods: ['GET'])]
    public function index(AdrFacturationUserRepository $adrFacturationUserRepository): Response
    {

        $user = $this->getUser(); // Récupérer l'utilisateur actuellement authentifié

        // Récupérer uniquement les adresses de livraison de l'utilisateur connecté
        $adrFacturationUsers = $adrFacturationUserRepository->findByUserId($user->getId());


        return $this->render('adr_facturation_user/index.html.twig', [
            'adr_facturation_users' => $adrFacturationUsers,
        ]);
    }

    #[Route('/new', name: 'app_adr_facturation_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, AdrFacturationUserRepository $adrFacturationUserRepository): Response
    {

        $user = $this->getUser(); // Récupérer l'utilisateur actuellement authentifié

        // Vérifier si l'utilisateur a déjà une adresse de facturation enregistrée
        $existingAddress = $adrFacturationUserRepository->findOneBy(['user_id' => $user->getId()]);
        if ($existingAddress !== null) {
            // Rediriger l'utilisateur vers la route show de son adresse de facturation existante
            $addressId = $existingAddress->getId();
            return new RedirectResponse($this->generateUrl('app_adr_facturation_user_show', ['id' => $addressId]));
        }

        $adrFacturationUser = new AdrFacturationUser();
        $adrFacturationUser->setUserId($user->getId()); // Associer l'ID de l'utilisateur à l'adresse de livraison
        $form = $this->createForm(AdrFacturationUserType::class, $adrFacturationUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($adrFacturationUser);
            $entityManager->flush();
            
            $this->addFlash('success', 'Votre adresse a bien été enregistrée');
            return $this->redirectToRoute('app_profil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('adr_facturation_user/new.html.twig', [
            'adr_facturation_user' => $adrFacturationUser,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_adr_facturation_user_show', methods: ['GET'])]
    public function show(AdrFacturationUser $adrFacturationUser): Response
    {
        return $this->render('adr_facturation_user/show.html.twig', [
            'adr_facturation_user' => $adrFacturationUser,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_adr_facturation_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AdrFacturationUser $adrFacturationUser, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdrFacturationUserType::class, $adrFacturationUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Votre adresse a bien été modifiée');
            return $this->redirectToRoute('app_profil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('adr_facturation_user/edit.html.twig', [
            'adr_facturation_user' => $adrFacturationUser,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_adr_facturation_user_delete', methods: ['POST'])]
    public function delete(Request $request, AdrFacturationUser $adrFacturationUser, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$adrFacturationUser->getId(), $request->request->get('_token'))) {
            $entityManager->remove($adrFacturationUser);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_adr_facturation_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
