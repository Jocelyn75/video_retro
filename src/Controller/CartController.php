<?php

namespace App\Controller;

use App\Service\TMDBService;
use App\Repository\StockRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/cart')]
class CartController extends AbstractController
{
    private $stockRepository;
    public function __construct(StockRepository $stockRepository)
    {
        $this->stockRepository = $stockRepository;
    }

    #[Route('/', name: 'app_cart')]
    public function index(SessionInterface $session, TMDBService $tmdbService, Request $request): Response
    {

        // Récupérer l'URL précédente
        $previousUrl = $request->headers->get('referer');
        // Stocker l'URL précédente dans la session
        $session->set('previous_url', $previousUrl);

        $cart = $session->get('cart', []);
        dump($cart);

        $cartDetails = [];
        $montantTotal = 0;
        foreach ($cart as $stockId => $quantity) {
            $stock = $this->stockRepository->find($stockId);
            if ($stock !== null) {

                $filmId = $stock->getFilms()->getFilmsApiId();
                if ($filmId !== null) {
                    $filmDetails = $tmdbService->getFilmDetails($filmId);
                    $filmTitle = $filmDetails['title'] ?? 'Titre non disponible';
                    $stock->titre = $filmTitle;
                } else {
                    $stock->titre = 'Titre non disponible';
                }
                
                $montant = $stock->getPrixReventeDefaut() * $quantity;
                $montantTotal += $montant;

                $cartDetails[] = [
                    'id' => $stock->getId(),
                    'titre' => $stock->titre,
                    'format' => $stock->getFormats()->getNomFormat(),
                    'prix' => $stock->getPrixReventeDefaut(),
                    'quantite' => $quantity,
                    'montant' => $montant,
                ];
            }
        }

        return $this->render('cart/index.html.twig', [
            'cartDetails' => $cartDetails,
            'montantTotal' => $montantTotal
        ]);
    }

    #[Route('/add', name: 'app_cart_new', methods: ['POST'])]
    public function add(Request $request, SessionInterface $session)
    {
        $stockId = $request->request->get('stock');
        $quantite = $request->request->get('quantite');

        // dump($stockId);
        // dd($quantite);

        $cart = $session->get('cart', []);

        if (isset($cart[$stockId])) {
            $cart[$stockId] += $quantite;
        }else{
            $cart[$stockId] = $quantite;
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('app_cart');

        // $panier = [
        //     7 => 3,
        //     9 => 12,
        //     15 => 4
        // ]
        // Key : id du produit
        // Value : quantité
    }

    #[Route('/update-quantity', name: 'app_cart_update_quantity', methods: ['POST'])]
    public function updateQuantity(Request $request, SessionInterface $session, StockRepository $stockRepository)
    {
        $stockId = $request->request->get('stockId');
        $changeQuantity = (int)$request->request->get('changeQuantity');

        $cart = $session->get('cart', []);

        if (isset($cart[$stockId])) {
            // Récupérer le stock correspondant
            $stock = $stockRepository->find($stockId);
    
            // Vérifier si le stock existe et s'il y a suffisamment de quantité en stock
            if ($stock && ($stock->getQuantiteStock() >= $cart[$stockId] + $changeQuantity)) {
                // Mettre à jour la quantité dans le panier
                $cart[$stockId] += $changeQuantity;
                if ($cart[$stockId] <= 0) {
                    unset($cart[$stockId]); // Remove item if quantity becomes zero or negative
                }
    
                $session->set('cart', $cart);
    
                return $this->redirectToRoute('app_cart');
            } else {
                // Rediriger avec un message d'erreur si la quantité en stock n'est pas suffisante
                $this->addFlash('error', 'Stock insuffisant au-delà de cette quantité.');
                return $this->redirectToRoute('app_cart');
            }
        }
    
        // Redirection par défaut
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/empty', name: 'app_cart_empty')]
    public function emptyCart(SessionInterface $session): RedirectResponse
    {
        // Supprimer tous les éléments du panier
        $session->remove('cart');

        // Rediriger vers la page du panier
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/remove/{stockId}', name: 'app_cart_remove')]
    public function removeProduct(SessionInterface $session, $stockId): RedirectResponse
    {
        $cart = $session->get('cart', []);

        if (isset($cart[$stockId])) {
            unset($cart[$stockId]);
            $session->set('cart', $cart);
        }

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/go-to-previous-film', name: 'go_to_previous_film')]
    public function goToPreviousFilm(SessionInterface $session): Response
    {
        // Récupérer l'URL précédente depuis la session
        $previousUrl = $session->get('previous_url');

        // Rediriger l'utilisateur vers l'URL précédente
        return $this->redirect($previousUrl);
    }
}

    // #[Route('/update-quantity', name: 'app_cart_update_quantity', methods: ['POST'])]
    // public function updateQuantity(Request $request, SessionInterface $session, StockRepository $stockRepository):JsonResponse
    // {
    //     $stockId = $request->request->get('stockId');
    //     $changeQuantity = (int)$request->request->get('changeQuantity');

    //     $cart = $session->get('cart', []);

    //     if (isset($cart[$stockId])) {
    //         // Récupérer le stock correspondant
    //         $stock = $stockRepository->find($stockId);
    
    //         // Vérifier si le stock existe et s'il y a suffisamment de quantité en stock
    //         if ($stock && ($stock->getQuantiteStock() >= $cart[$stockId] + $changeQuantity)) {
    //             // Mettre à jour la quantité dans le panier
    //             $cart[$stockId] += $changeQuantity;
    //             if ($cart[$stockId] <= 0) {
    //                 unset($cart[$stockId]); // Retirer le produit si la quantité atteint 0.
    //             }
    
    //             $session->set('cart', $cart);

    //             $montantTotal = $this->calculerMontantTotal($session, $stockRepository);
    
    //             // Retourner les données mises à jour en JSON
    //             return new JsonResponse(['success' => true, 'quantity' => $cart[$stockId], 'montantTotal' => $montantTotal]);
    //         } else {
    //             // Retourner une réponse JSON avec un message d'erreur si la quantité en stock n'est pas suffisante
    //             return new JsonResponse(['success' => false, 'message' => 'Stock insuffisant au-delà de cette quantité.'], 400);
    //         }
    //     }
    
    //     // Redirection par défaut
    //     return new JsonResponse(['success' => false, 'message' => 'Stock non trouvé.'], 404);
    // }

    // // Fonction pour calculer le montant total du panier
    // private function calculerMontantTotal(SessionInterface $session, StockRepository $stockRepository): float
    // {
    //     $cart = $session->get('cart', []);
    //     $montantTotal = 0;

    //     foreach ($cart as $stockId => $quantity) {
    //         $stock = $stockRepository->find($stockId);
    //         if ($stock) {
    //             $montantTotal += $stock->getPrixReventeDefaut() * $quantity;
    //         }
    //     }

    //     return $montantTotal;
    // }
