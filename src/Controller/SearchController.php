<?php

namespace App\Controller;

use App\Form\SearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search', methods:['GET'])]
    public function index(Request $request, HttpClientInterface $client): Response
    {
        $data = $request->query->all();
        // $keyword = $data['keyword'];

        $multi = $data['search']['keyword'];

        // dd( $response1 = $client->request(
        //     'GET',
        //     'https://api.themoviedb.org/3/movie/122917?api_key=3a95b6e18efaa9f408509a7748094742'
        // ));

        $apiResponse = $client->request('GET', "https://api.themoviedb.org/3/search/multi?query={$multi}&api_key=3a95b6e18efaa9f408509a7748094742");
        // https://api.themoviedb.org/3/search/multi?query=cameron&include_adult=false&language=en-US&page=1' \
        // $apiResponse = $client->request('GET', "https://api.themoviedb.org/3/search/multi/{$multi}?include_adult=false&language=en-US&page=1?api_key=3a95b6e18efaa9f408509a7748094742");

        $apiResponseArray = $apiResponse->toArray();
        
        $data = $apiResponseArray['results'];

        // dd($data);

        // $films = [];

        // foreach ($data as $film) 
        // {

        //     if () {
        //         # code...
        //     }
        //     $filmFilter = [];

        //     $filmFilter[] = $film['title'];
        //     $filmFilter[] = $film['overview'];
        //     $filmFilter[] = $film['media_type'];

        //     // dd($filmFilter);

        //     $films[] = $filmFilter;
        // }

        // dd($films);

        // dd($data[0]['title']);

        return $this->render('search/index.html.twig', [
            'data' => $data,
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
