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
    /**
     * index
     *
     * @param  mixed $request
     * @param  mixed $client
     * @return Response
     */
    public function searchByKeyword(Request $request): Response
    {
        $data = $request->query->all();
        $search = isset($data['search']['keyword']) ? $data['search']['keyword'] : null;

        // 'error' est défini dans TMDBService.
        if ($search) {
            $response = $this->tmdbService->searchFilms($search);
            if (isset($response['error'])) {
                $error = $response['error'];
                $films = [];
            } else {
                $films = $response;
            }
        } else {
            $films = [];
            $error = 'Aucun mot-clé de recherche fourni.';
        }        
        $imageUrl = $this->tmdbService->getImageUrl();


        return $this->render('search/index.html.twig', [
            'films' => $films,
            'imageUrl' => $imageUrl,
            'error' => $error ?? null,
        ]);
    }

    /**
     * getSearchBar
     *
     * @return Response
     */
    public function getSearchBar() : Response
    {
        
        $form = $this->createForm(SearchType::class, null, [
            'keyword' =>true,
            "method" => "GET",
            "csrf_protection" => false
        ]);
        return $this->render('search/_search_bar.html.twig', [
            'form' => $form->createView()            
        ]);
    }

    #[Route('/search/by-year', name: 'app_search_by_year', methods:['GET'])]        
    /**
     * indexYear
     *
     * @param  mixed $request
     * @return Response
     */
    public function searchByYear(Request $request): Response
        {
            $data = $request->query->all();
            
            //Si une année est renseignée, alors $search prend la valeur renseignée, sinon $search est nul.
            $search = isset($data['search']['year']) ? $data['search']['year'] : null;

            if (!is_numeric($search) || intval($search) != $search || strlen($search) !== 4)  {
                $this->addFlash('error', 'Veuillez entrer une année valide.');
                return $this->redirectToRoute('home');
            }

            if ($search) {
                $films = $this->tmdbService->getPopularFilmsByYear($search);
            } else {
                $films = [];
            }
            
            $imageUrl = $this->tmdbService->getImageUrl();

            return $this->render('search/index.html.twig', [
                'films' => $films,
                'imageUrl' => $imageUrl,
            ]);
        }


        /**
     * getSearchByYear
     *
     * @return Response
     */
    public function getSearchByYear() : Response
    {
        $form = $this->createForm(SearchType::class, null, [
            'year' =>true,
            'keyword' => false,
            "method" => "GET",
            "csrf_protection" => false
        ]);
        return $this->render('search/_search_by_year.html.twig', [
            'form' => $form->createView()
        ]);
    }


}
