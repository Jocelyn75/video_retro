<?php

namespace App\Controller;

use App\Entity\Films;
use App\Entity\Stock;
use App\Form\FilmsType;
use App\Service\TMDBService;
use App\Repository\FilmsRepository;
use App\Repository\FormatsRepository;
use App\Repository\StockRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

// #[Route('/films')]
class FilmsController extends AbstractController
{
    private $tmdbService;

    // Injecter le service TMDBService dans le constructeur    
    public function __construct(TMDBService $tmdbService)
    {
        $this->tmdbService = $tmdbService;
    }
    
    #[Route('/admin/films', name: 'app_films_index', methods: ['GET'])]    
    /**
     * index
     *
     * @param  mixed $filmsRepository
     * @return Response
     */
    public function index(FilmsRepository $filmsRepository): Response
    {
        return $this->render('films/index.html.twig', [
            'films' => $filmsRepository->findAll(),
        ]);
    }

    #[Route('films/{id}', name: 'app_films_show', methods: ['GET'])]    
    /**
     * show
     *
     * @param  mixed $id
     * @param  mixed $stockRepository
     * @param  mixed $filmsRepository
     * @param  mixed $formatsRepository
     * @return Response
     */
    public function show(string $id, StockRepository $stockRepository, FilmsRepository $filmsRepository, FormatsRepository $formatsRepository): Response
    {
        $filmsShow = $this->tmdbService->getFilmDetails($id);
        $credits = $this->tmdbService->getFilmCredits($id);
        $data = $this->tmdbService->getFilmProviders($id);

        // Vérification des erreurs dans les requêtes.
        if (isset($filmsShow['error'])) {
            $this->addFlash('error', $filmsShow['error']);
            return $this->redirectToRoute('app_films_show');
        }

        if (isset($credits['error'])) {
            $this->addFlash('error', $credits['error']);
            return $this->redirectToRoute('app_films_show');
        }

        if (isset($data['error'])) {
            $this->addFlash('error', $data['error']);
            return $this->redirectToRoute('app_films_show');
        }

        // // Récupérer le film correspondant à l'$id fourni
        $film = $filmsRepository->findOneBy(['films_api_id' => $id]);
        $stock = $stockRepository->findAll();
        $format = $formatsRepository->findAll();

        $imageUrl = $this->tmdbService->getImageUrl();

        //Crédits
        $director = array_filter($credits['crew'], function ($crewMember) {
            return $crewMember['job'] === 'Director';
        });
        
        $cast = array_slice($credits['cast'], 0, 5);

        //Providers
        $providers = $data['results']['FR'] ?? "";

        $rent = $providers['rent'] ?? "";
        $buy = $providers['buy'] ?? "";
        $flatrate = $providers['flatrate'] ?? "";

        return $this->render('films/show.html.twig', [
            'filmsShow' => $filmsShow,
            'imageUrl' => $imageUrl,
            'director' => $director,
            'cast' => $cast,
            'rent' => $rent,
            'buy' => $buy,
            'flatrate' => $flatrate,
            'film' => $film,
            'stock' => $stock,
            'format' => $format, 
        ]);
    }

    #[Route('/admin/films/{id}/edit', name: 'app_films_edit', methods: ['GET', 'POST'])]    
    /**
     * edit
     *
     * @param  mixed $request
     * @param  mixed $film
     * @param  mixed $entityManager
     * @return Response
     */
    public function edit(Request $request, Films $film, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FilmsType::class, $film);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_films_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('films/edit.html.twig', [
            'film' => $film,
            'form' => $form,
        ]);
    }

    #[Route('/admin/films/{id}', name: 'app_films_delete', methods: ['POST'])]    
    /**
     * delete
     *
     * @param  mixed $request
     * @param  mixed $film
     * @param  mixed $entityManager
     * @return Response
     */
    public function delete(Request $request, Films $film, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$film->getId(), $request->request->get('_token'))) {
            $entityManager->remove($film);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_films_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * Route désactivée pour n'ajouter de nouveaux produits que via l'API.
     * 
     * */
    #[Route('/admin/films/new', name: 'app_films_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $film = new Films();
        $form = $this->createForm(FilmsType::class, $film);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($film);
            $entityManager->flush();

            return $this->redirectToRoute('app_films_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('films/new.html.twig', [
            'film' => $film,
            'form' => $form,
        ]);
    }
}
    
    /**
     * Route show originelle du controller.
    */
    // #[Route('/{id}', name: 'app_films_show', methods: ['GET'])]
    // public function show(Films $film): Response
    // {
    //     return $this->render('films/show.html.twig', [
    //         'film' => $film,
    //     ]);
    // }


