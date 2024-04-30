<?php

namespace App\Controller;

use App\Entity\Films;
use App\Entity\Stock;
use App\Entity\Formats;
use App\Form\StockType;
use App\Service\TMDBService;
use App\Repository\StockRepository;
use App\Repository\FormatsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/stock')]
class StockController extends AbstractController
{

    private $tmdbService;

    public function __construct(TMDBService $tmdbService)
    {
        $this->tmdbService = $tmdbService;
    }

    #[Route('/', name: 'app_stock_index', methods: ['GET'])]
    public function index(StockRepository $stockRepository, Request $request, PaginatorInterface $paginator): Response
    {
    // php $stocks = $stockRepository->findAll(); ne fonctionne pas, elle ne retourne pas une query mais un tableau d'objets qui ne peut pas être utilisé avec paginator.
    $stocksQuery = $stockRepository->createQueryBuilder('s')->getQuery();
    $pagination = $paginator->paginate(
        $stocksQuery,
        $request->query->getInt('page', 1), /*numéro de la page*/
        15 /*résultats par page : garder un multiple de 3 pour avoir la VHS, le DVD et le Blu-Ray de chaque film sur la même page.*/
    );

    foreach ($pagination as $stock) { // **Modification de la boucle**
        $filmId = $stock->getFilms()->getFilmsApiId();
        if ($filmId !== null) {
            $filmDetails = $this->tmdbService->getFilmDetails($filmId);
            $filmTitle = $filmDetails['title'] ?? 'Titre non disponible';
            $stock->titre = $filmTitle;
        } else {
            $stock->titre = 'Titre non disponible';
        }
    }

    // dd($filmTitles);

    return $this->render('stock/index.html.twig', [
        'pagination' => $pagination, // **Modification du passage de variable à la vue**
    ]);
    }

    #[Route('/new', name: 'app_stock_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $stock = new Stock();
        $form = $this->createForm(StockType::class, $stock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($stock);
            $entityManager->flush();

            return $this->redirectToRoute('app_stock_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('stock/new.html.twig', [
            'stock' => $stock,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_stock_show', methods: ['GET'])]
    public function show(Stock $stock, TMDBService $tmdbService): Response
    {
        $filmId = $stock->getFilms()->getFilmsApiId();
        $filmTitle = 'Titre non disponible';
        if ($filmId !== null) {
            $filmDetails = $tmdbService->getFilmDetails($filmId);
            $filmTitle = $filmDetails['title'] ?? 'Titre non disponible';
        }

    return $this->render('stock/show.html.twig', [
        'stock' => $stock,
        'filmTitle' => $filmTitle, // Passer le titre du film à la vue
    ]);
    }

    #[Route('/{id}/edit', name: 'app_stock_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Stock $stock, EntityManagerInterface $entityManager, TMDBService $tmdbService): Response
    {
        $form = $this->createForm(StockType::class, $stock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_stock_index', [], Response::HTTP_SEE_OTHER);
        }

        $filmId = $stock->getFilms()->getFilmsApiId();
        $filmTitle = 'Titre non disponible';
        if ($filmId !== null) {
            $filmDetails = $tmdbService->getFilmDetails($filmId);
            $filmTitle = $filmDetails['title'] ?? 'Titre non disponible';
        }
    
        return $this->render('stock/edit.html.twig', [
            'stock' => $stock,
            'form' => $form->createView(),
            'filmTitle' => $filmTitle, // Passer le titre du film à la vue
        ]);
    }

    #[Route('/{id}', name: 'app_stock_delete', methods: ['POST'])]
    public function delete(Request $request, Stock $stock, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$stock->getId(), $request->request->get('_token'))) {
            $entityManager->remove($stock);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_stock_index', [], Response::HTTP_SEE_OTHER);
    }
}


    //Route utilisée pour récupérer ma liste de films et créer les produits dans ma table stock. 
    // #[Route('/getfilms', name: 'getfilms', methods: ['GET', 'POST'])]
    // public function getfilms(HttpClientInterface $client, EntityManagerInterface $entityManager, FormatsRepository $formatsRepository)
    // {
    //     // Nombre total de pages à récupérer
    //     $totalPages = 26;
    
    //     // Parcourir toutes les pages
    //     for ($page = 1; $page <= $totalPages; $page++) {
    //         // Récupérer les films de la page actuelle
    //         $apiResponse = $client->request('GET', "https://api.themoviedb.org/3/list/8296832?language=fr&api_key={$_ENV['TMDB_API']}&page=$page");
        
    //         $apiResponseArray = $apiResponse->toArray();
    //         $films = $apiResponseArray['items'];
    
    //         // Parcourir les films de la page actuelle
    //         foreach ($films as $filmApi) {
    //             $film = new Films();
    //             $film->setFilmsApiId($filmApi['id']);
    //             $entityManager->persist($film);
    
    //             // Parcourir tous les formats
    //             $formats = $formatsRepository->findAll();
    //             foreach ($formats as $format) {
    //                 $stock = new Stock();
    //                 $stock->setFormats($format);
    //                 $stock->setFilms($film);
    //                 $stock->setQuantiteStock(0);
    //                 $entityManager->persist($stock);
    //             }
    //         }
    //     }
    
    //     $entityManager->flush();
    // }
    


//Code commenté pour afficher la liste des produits 
//     #[Route('/', name: 'app_stock_index', methods: ['GET'])] // Définit la route pour cette méthode de contrôleur
// public function index(StockRepository $stockRepository, Request $request, PaginatorInterface $paginator): Response // Définit la méthode index du contrôleur, qui prend un StockRepository en argument et renvoie une réponse HTTP
// {
//     $stocks = $stockRepository->findAll(); // Récupère tous les stocks depuis le repository
//     $filmTitles = []; // Initialise un tableau vide pour stocker les titres des films

//     // Pour chaque stock, récupère le titre du film en utilisant le service TMDBService
//     foreach ($stocks as $stock) {
//         $filmId = $stock->getFilms()->getFilmsApiId(); // Récupère l'identifiant du film associé à ce stock
//         // Vérifie si l'identifiant du film est défini
//         if ($filmId !== null) {
//             $filmDetails = $this->tmdbService->getFilmDetails($filmId); // Utilise le service TMDBService pour obtenir les détails du film
//             $filmTitle = $filmDetails['title'] ?? 'Titre non disponible'; // Récupère le titre du film s'il existe, sinon utilise une chaîne par défaut
//             $filmTitles[$stock->getId()] = $filmTitle; // Stocke le titre du film dans le tableau filmTitles avec l'ID du stock comme clé
//         } else {
//             $filmTitles[$stock->getId()] = 'Titre non disponible'; // Si l'identifiant du film n'est pas défini, utilise une chaîne par défaut
//         }
//     }

//     // Renvoie une réponse HTML en rendant le template 'stock/index.html.twig' avec les données des stocks et des titres de films
//     return $this->render('stock/index.html.twig', [
//         'stocks' => $stocks, // Passe les stocks à la vue Twig
//         'filmTitles' => $filmTitles, // Passe les titres des films à la vue Twig
//         ]);
//     }


// METTRE A JOUR LES PRIX DE REVENTE DES PRODUITS
// #[Route('/admin/stock')]
// class StockController extends AbstractController
// {

//     private EntityManagerInterface $entityManager;
//     private TMDBService $tmdbService;

//     public function __construct(EntityManagerInterface $entityManager, TMDBService $tmdbService)
//     {
//         $this->entityManager = $entityManager;
//         $this->tmdbService = $tmdbService;
//     }

//     #[Route('/update-prices', name: 'update_prices', methods: ['GET'])]
//     public function updatePrices(): Response
//     {
//         // Récupérer le Repository pour la table des formats
//         $formatRepository = $this->entityManager->getRepository(Formats::class);

//         // Récupérer tous les formats
//         $formats = $formatRepository->findAll();

//         // Parcourir les formats
//         foreach ($formats as $format) {
//             // Définir le prix en fonction du nom du format
//             switch ($format->getNomFormat()) {
//                 case 'VHS':
//                     $prix = 8;
//                     break;
//                 case 'DVD':
//                     $prix = 13;
//                     break;
//                 case 'BLU-RAY':
//                     $prix = 18;
//                     break;
//                 default:
//                     // Cas par défaut, si le nom du format n'est pas reconnu
//                     $prix = 0; // Passer à la prochaine itération de la boucle externe
//             }

//             // Récupérer les produits correspondant à ce format
//             $products = $this->entityManager->getRepository(Stock::class)->findBy(['formats' => $format]);

//             // Mettre à jour les prix des produits
//             foreach ($products as $product) {
//                 $product->setPrixReventeDefaut($prix);
//             }
//         }

//         // Appliquer les changements à la base de données
//         $this->entityManager->flush();

//         // Retourner une réponse
//         return new Response('Les prix ont été mis à jour avec succès.');
//     }


