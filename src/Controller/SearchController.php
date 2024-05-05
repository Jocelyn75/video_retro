<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Service\TMDBService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    private $tmdbService;

    public function __construct(TMDBService $tmdbService)
    {
        $this->tmdbService = $tmdbService;
    }

    #[Route('/search', name: 'app_search', methods:['GET'])]
    public function index(Request $request, HttpClientInterface $client): Response
    {
        $data = $request->query->all();
        $search = $data['search']['keyword'];

        // Utiliser le service TMDBService pour effectuer la recherche de films
        $films = $this->tmdbService->searchFilms($search);
        
        $imageUrl = $this->tmdbService->getImageUrl();

        return $this->render('search/index.html.twig', [
            'films' => $films,
            'imageUrl' => $imageUrl,
        ]);
    }

    public function getSearchBar() : Response
    {
        $form = $this->createForm(SearchType::class, null, [
            "method" => "GET",
            "csrf_protection" => false
        ]);
        return $this->render('search/_search_bar.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
