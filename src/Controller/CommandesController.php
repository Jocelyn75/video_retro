<?php

namespace App\Controller;

use App\Entity\Commandes;
use App\Entity\DetailsCommandes;
use App\Entity\User;
use App\Form\CommandesType;
use App\Repository\CommandesRepository;
use App\Repository\StockRepository;
use App\Service\CommandeRefGenerator;
use App\Service\TMDBService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commandes')]
class CommandesController extends AbstractController
{
    #[Route('/', name: 'app_commandes_index', methods: ['GET'])]
    public function index(CommandesRepository $commandesRepository): Response
    {
        return $this->render('commandes/index.html.twig', [
            'commandes' => $commandesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_commandes_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em, SessionInterface $session, StockRepository $stockRepository, TMDBService $tmdbService, CommandeRefGenerator $commandeRef): Response
    {
        //On récupère le panier
        $cart = $session->get('cart', []);

        // On crée une nouvelle commande
        $commande = new Commandes();

        // On remplit la commande
        $commande->setUser($this->getUser());
        $commande->setReference($commandeRef->generateReference());
        
        //On boucle le panier pour créer les détails de la commande.
        foreach ($cart as $stockId => $quantity) {
            $detailsCommandes = new DetailsCommandes();

            // On va chercher le produit (avec la requête API pour obtenir le titre).
            $stock = $stockRepository->find($stockId);
            $filmId = $stock->getFilms()->getFilmsApiId();
            $stock->titre = $tmdbService->getFilmTitle($filmId);
            
            $prix = $stock->getPrixReventeDefaut();

            //On crée le détails de commande
            $detailsCommandes->setStockId($stockId);
            $detailsCommandes->setPrixUnitaire($prix);
            $detailsCommandes->setQuantiteCmd($quantity);

            //On ajoute dans la commande les détails de la commande.
            $commande->addDetailsCommande($detailsCommandes);
        }

        // Avec persist on crée les requêtes.
        $em->persist($commande);
        // Avec flush, on les exécutes
        $em->flush();
        $session->remove('cart');



        // $form = $this->createForm(CommandesType::class, $commande);
        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {
        //     $entityManager->persist($commande);
        //     $entityManager->flush();

        //     return $this->redirectToRoute('app_commandes_index', [], Response::HTTP_SEE_OTHER);
        
        $this->addFlash('message', 'Commande créée avec succès');
        return $this->render('commandes/new.html.twig', [
            'controller_name' => 'CommandesController',
            // 'commande' => $commande,
            // 'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commandes_show', methods: ['GET'])]
    public function show(Commandes $commande): Response
    {
        return $this->render('commandes/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commandes_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commandes $commande, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommandesType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commandes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commandes/edit.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commandes_delete', methods: ['POST'])]
    public function delete(Request $request, Commandes $commande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commandes_index', [], Response::HTTP_SEE_OTHER);
    }
}
