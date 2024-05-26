<?php

namespace App\Controller;

use App\Service\TMDBService;
use App\Entity\AdrLivraisonUser;
use App\Repository\StockRepository;
use App\Repository\LivreurRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\AdrLivraisonUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/profile/cart')]
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
        
        $cartDetails = [];
        $montantTotal = 0;
        // k => v : stockID = k ; quantity = v
        foreach ($cart as $stockId => $quantity) { 
            $stock = $this->stockRepository->find($stockId);
            if ($stock !== null) {

                $filmId = $stock->getFilms()->getFilmsApiId();
                $stock->titre = $tmdbService->getFilmTitle($filmId);
                $filmDetails = $tmdbService->getFilmDetails($filmId);
                $imageUrl = $tmdbService->getImageUrl() . 'w92' . $filmDetails['poster_path'];

                $montant = $stock->getPrixReventeDefaut() * $quantity;
                $montantTotal += $montant;

                $cartDetails[] = [
                    'imageUrl' => $imageUrl,
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

    #[Route('/checkout', name: 'app_cart_checkout')]    
        
    public function cartCheckout(SessionInterface $session, Request $request, LivreurRepository $livreurRepository, AdrLivraisonUserRepository $adrLivraisonUserRepository, TMDBService $tmdbService, EntityManagerInterface $em): Response {
        
        // Récupérer le contenu du panier depuis la session
        $user = $this->getUser();
        $cart = $session->get('cart', []);
    
        $cartDetails = [];
        $montantTotal = 0;
        $livreurs = $livreurRepository->findAll();
        $adrsLivraisonUser = $em->getRepository(AdrLivraisonUser::class)->findBy(['user' => $user]);
        
        // Vérifier si l'adresse de facturation existe pour l'utilisateur
        $adrFacturationUser = $user->getAdrFacturationUser();
        if ($adrFacturationUser === null) {
            $this->addFlash('error', 'Veuillez ajouter une adresse de facturation pour pouvoir passer la commande');
            return $this->redirectToRoute('app_adr_facturation_user_show');
        } else {
            //1er paramètre = clé de la valeur stockée, 2e paramètre = valeur stockée
            $session->set('adrFacturationUser', $adrFacturationUser);
        }

        // Traiter les éléments du panier
        foreach ($cart as $stockId => $quantity) {
            $stock = $this->stockRepository->find($stockId);
            if ($stock !== null) {
                $filmId = $stock->getFilms()->getFilmsApiId();
                $stock->titre = $tmdbService->getFilmTitle($filmId);
                $filmDetails = $tmdbService->getFilmDetails($filmId);
                $imageUrl = $tmdbService->getImageUrl() . 'w92' . $filmDetails['poster_path'];
                $montant = $stock->getPrixReventeDefaut() * $quantity;
                $montantTotal += $montant;
    
                $cartDetails[] = [
                    'imageUrl' => $imageUrl,
                    'id' => $stock->getId(),
                    'titre' => $stock->titre,
                    'format' => $stock->getFormats()->getNomFormat(),
                    'prix' => $stock->getPrixReventeDefaut(),
                    'quantite' => $quantity,
                    'montant' => $montant,
                ];
            }
        }

        $montantTotalCommande = $montantTotal;        

        // Traiter les sélections de livreur et d'adresse de livraison
        if ($request->isMethod('POST')) {
            $livreurId = $request->request->get('livreur');
            $livreur = $livreurRepository->find($livreurId);
            $adrLivraisonUserId = $request->request->get('adrLivraisonUser');
            $adrLivraisonUser = $adrLivraisonUserRepository->find($adrLivraisonUserId);
        
            if ($livreur !== null) {
                 // Ajouter les frais de livraison au montant total de la commande
                $montantTotalCommande += $livreur->getPrix();
                $this->addFlash('success', 'Vos choix ont bien été enregistrés.');
            }

            if ($adrLivraisonUser !== null) {
                $session->set('adrLivraisonUser', $adrLivraisonUser);
                $session->set('adrLivraisonUserId', $adrLivraisonUserId);
            }

            $session->set('livreur', $livreur); 
            $session->set('montantTotalCommande', $montantTotalCommande);
        
        }

        return $this->render('cart/cart_checkout.html.twig', [
            'cartDetails' => $cartDetails,
            'montantTotal' => $montantTotal,
            'livreurs' => $livreurs,
            'montantTotalCommande' => $montantTotalCommande,
            'adrsLivraisonUser' => $adrsLivraisonUser,
            'adrFacturationUser' => $adrFacturationUser,            
        ]);
    }
    
    #[Route('/add', name: 'app_cart_new', methods: ['POST'])]    
    public function add(Request $request, SessionInterface $session)
    {
        $stockId = $request->request->get('stock');
        $quantite = $request->request->get('quantite');

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
            // Récupérer le stock correspondant à l'id
            $stock = $stockRepository->find($stockId);
    
            // Vérifier si le stock existe et s'il y a suffisamment de quantité en stock
            if ($stock && ($stock->getQuantiteStock() >= $cart[$stockId] + $changeQuantity)) {
                // Mettre à jour la quantité dans le panier
                $cart[$stockId] += $changeQuantity;
                if ($cart[$stockId] <= 0) {
                    unset($cart[$stockId]); //Enlever le produit du panier si la quantité arrive à zéro ou moins
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
    /**
     * goToPreviousFilm
     *
     * @param  mixed $session
     * @return Response
     */
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



        // public function cartValidation(SessionInterface $session, TMDBService $tmdbService, Request $request, LivreurRepository $livreurRepository, AdrLivraisonUserRepository $adrLivraisonUserRepository): Response
    // {

    //     $this->denyAccessUnlessGranted('ROLE_USER');
        
    //     if ($request->isMethod('POST')) {
    //     $livreurId = $request->request->get('livreur');
    //     $adresseLivraisonId = $request->request->get('adresseLivraison');
    //     }
    //     //Utilisation du referer dans le FIL D'ARIANE.
    //     // Récupérer l'URL précédente
    //     $previousUrl = $request->headers->get('referer');
    //     // Stocker l'URL précédente dans la session
    //     $session->set('previous_url', $previousUrl);

    //     // PANIER
    //     $cart = $session->get('cart', []);

    //     $cartDetails = [];
    //     $montantTotal = 0;
    //     foreach ($cart as $stockId => $quantity) {
    //         $stock = $this->stockRepository->find($stockId);
    //         if ($stock !== null) {

    //             $filmId = $stock->getFilms()->getFilmsApiId();
    //             $stock->titre = $tmdbService->getFilmTitle($filmId);
    //             $filmDetails = $tmdbService->getFilmDetails($filmId);
    //             $imageUrl = $tmdbService->getImageUrl() . 'w92' . $filmDetails['poster_path'];

    //             $montant = $stock->getPrixReventeDefaut() * $quantity;
    //             $montantTotal += $montant;

    //             $cartDetails[] = [
    //                 'imageUrl' => $imageUrl,
    //                 'id' => $stock->getId(),
    //                 'titre' => $stock->titre,
    //                 'format' => $stock->getFormats()->getNomFormat(),
    //                 'prix' => $stock->getPrixReventeDefaut(),
    //                 'quantite' => $quantity,
    //                 'montant' => $montant,
    //             ];
    //         }
    //     }

    //     // Initialiser le montant total de la commande au montant total du panier
    //     $montantTotalCommande = $montantTotal;
    //     $livreurs = $livreurRepository->findAll();

    //     // ADRESSE DE LIVRAISON
    //     $user = $this->getUser();
    //     $adressesLivraison = $user->getAdrLivraisonUser();
    //     $adressesFacturation = $user->getAdrFacturationUser();
        
    //     // LIVREUR
    //     if ($request->isMethod('POST')) {
    //         $livreurId = $request->request->get('livreur');
    //         $livreur = $livreurRepository->find($livreurId);
    //         $this->addFlash('success', 'Vos choix ont bien été enregistrés.');
    //         if ($livreur !== null) {
    //             // Ajouter les frais de livraison au montant total de la commande
    //             $montantTotalCommande += $livreur->getPrix();
    //         // } else {
    //         //     // Gérer le cas où aucun livreur n'est sélectionné
    //         //     // Peut-être afficher un message d'erreur ou prendre une autre action appropriée
    //         }

    //         // Stocker le montant total de la commande dans la session
    //         $session->set('montantTotalCommande', $montantTotalCommande);

    //         // ADRESSES DE LIVRAISON
    //         $adresseLivraison = $adrLivraisonUserRepository->find($adresseLivraisonId);
    //         if ($adresseLivraison !== null) {
    //             // Utilisez l'adresse de livraison sélectionnée comme vous le souhaitez
    //             // Par exemple, vous pouvez récupérer les détails de l'adresse et les utiliser dans votre logique de commande
    //         } else {
    //             // Gérer le cas où aucune adresse de livraison n'est sélectionnée
    //             // Peut-être afficher un message d'erreur ou prendre une autre action appropriée
    //         }
    //     }

    //     // Récupérer toutes les adresses de livraison
    //     // $adressesLivraison = $adrLivraisonUserRepository->findAll();

    //     return $this->render('cart/cart_checkout.html.twig', [
    //         'cartDetails' => $cartDetails,
    //         'montantTotal' => $montantTotal,
    //         'livreurs' => $livreurs, 
    //         'montantTotalCommande' => $montantTotalCommande,
    //         'adressesLivraison' => $adressesLivraison,
    //         'adressesFacturation' => $adressesFacturation
    //     ]);
    // }
