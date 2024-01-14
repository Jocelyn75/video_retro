<?php

namespace App\Controller;

use App\Entity\DetailsCommandes;
use App\Form\DetailsCommandesType;
use App\Repository\DetailsCommandesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/details/commandes')]
class DetailsCommandesController extends AbstractController
{
    #[Route('/', name: 'app_details_commandes_index', methods: ['GET'])]
    public function index(DetailsCommandesRepository $detailsCommandesRepository): Response
    {
        return $this->render('details_commandes/index.html.twig', [
            'details_commandes' => $detailsCommandesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_details_commandes_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $detailsCommande = new DetailsCommandes();
        $form = $this->createForm(DetailsCommandesType::class, $detailsCommande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($detailsCommande);
            $entityManager->flush();

            return $this->redirectToRoute('app_details_commandes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('details_commandes/new.html.twig', [
            'details_commande' => $detailsCommande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_details_commandes_show', methods: ['GET'])]
    public function show(DetailsCommandes $detailsCommande): Response
    {
        return $this->render('details_commandes/show.html.twig', [
            'details_commande' => $detailsCommande,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_details_commandes_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DetailsCommandes $detailsCommande, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DetailsCommandesType::class, $detailsCommande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_details_commandes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('details_commandes/edit.html.twig', [
            'details_commande' => $detailsCommande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_details_commandes_delete', methods: ['POST'])]
    public function delete(Request $request, DetailsCommandes $detailsCommande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$detailsCommande->getId(), $request->request->get('_token'))) {
            $entityManager->remove($detailsCommande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_details_commandes_index', [], Response::HTTP_SEE_OTHER);
    }
}
