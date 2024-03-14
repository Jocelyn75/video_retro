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
    // On utilise HttpclientInterface pour se connecter à l'API, on lui demande d'écouter  $client. On injecte à Request l'objet $request. 
    public function index(Request $request, HttpClientInterface $client): Response
    {
        $data = $request->query->all();

        // La variable $multi contient le contenu de la recherche effectuée. Search correspond à la recherche et keyword aux mots-clés recherchés.
        $multi = $data['search']['keyword'];

        // $client fait une requête vers l'API. $multi est utilisée pour rendre la requête dynamique. $apiResponse stocke le contenu de la réponse.
        $apiResponse = $client->request('GET', "https://api.themoviedb.org/3/search/multi?query={$multi}&api_key={$_ENV['TMDB_API']}");

        // La réponse est convertie en tableau pour récupérer la réponse sous forme de tableau.
        $apiResponseArray = $apiResponse->toArray();
        
        // Dans $data, on récupère la valeur associée à la clé results dans $apiResponseArray. 
        $data = $apiResponseArray['results'];

        // Hello

        //on passe le tableau à la vue.
        return $this->render('search/index.html.twig', [
            'data' => $data,
        ]);
    }

    //La méthode getSearchBar ne va pas retourner de réponse.
    public function getSearchBar() : Response
    {
        //On demande à SearchController de créer un formulaire de type Search. On indique null le paramètre qui correspond à l'entité, puisqu'il n'y a pas d'entité, puis on indique dans le 3e paramètre qui est un tableau qu'on utilise la méthode GET, car la méthode par défaut est POST. 
        $form = $this->createForm(SearchType::class, null, [
            "method" => "GET",
            // Désactivation du token cross site request forgery.
            "csrf_protection" => false
        ]);
        //Appelée, cette méthode retourne le contenu du formulaire du fichier _search_bar.html.twig. Le formulaire s'affiche dans le fichier _search_bar.html.
        return $this->render('search/_search_bar.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
