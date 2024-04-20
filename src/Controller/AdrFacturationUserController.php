<?php

namespace App\Controller;

use App\Entity\AdrFacturationUser;
use App\Form\AdrFacturationUserType;
use App\Repository\AdrFacturationUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/adr/facturation/user')]
class AdrFacturationUserController extends AbstractController
{
    #[Route('/', name: 'app_adr_facturation_user_index', methods: ['GET'])]
    public function index(AdrFacturationUserRepository $adrFacturationUserRepository): Response
    {
        return $this->render('adr_facturation_user/index.html.twig', [
            'adr_facturation_users' => $adrFacturationUserRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_adr_facturation_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $adrFacturationUser = new AdrFacturationUser();
        $form = $this->createForm(AdrFacturationUserType::class, $adrFacturationUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($adrFacturationUser);
            $entityManager->flush();

            return $this->redirectToRoute('app_adr_facturation_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('adr_facturation_user/new.html.twig', [
            'adr_facturation_user' => $adrFacturationUser,
            'form' => $form,
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

            return $this->redirectToRoute('app_adr_facturation_user_index', [], Response::HTTP_SEE_OTHER);
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
