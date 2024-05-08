<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class TMDBService
{
    private $client;
    private $imageUrl = 'https://image.tmdb.org/t/p/';

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    //Méthode pour accéder au préfixe des urls pour les requêtes sur les images.
    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    /// Méthode pour la barre de recherche : recherche d'un film par mots-clés.
    public function searchFilms(string $searchQuery): array
    {
        $apiResponse = $this->client->request('GET', 'https://api.themoviedb.org/3/search/movie', [
            'query'=>[
                'api_key' => $_ENV['TMDB_API'],
                'query' => $searchQuery,
                'language' => 'fr',
                'page' => 1,
                ]
            ]);
        $apiResponseArray = $apiResponse->toArray();
        return $apiResponseArray['results'];
    }

    //Méthode pour récupérer la liste des films populaires par année
    public function getPopularMoviesByYear($year): array
    {
        $apiResponse = $this->client->request('GET', 'https://api.themoviedb.org/3/discover/movie', [
            'query' => [
                'api_key' => $_ENV['TMDB_API'],
                'primary_release_year' => $year,
                'sort_by' => 'popularity.desc', // Trier par popularité décroissante
                'page' => 1, // Vous pouvez ajuster cela pour obtenir plus de résultats
            ],
        ]);

        $apiResponseArray = $apiResponse->toArray();
        return $apiResponseArray['results'];
    }


    // Méthode pour la fiche film : récupération des données d'un film à partir de son id
    public function getFilmDetails(string $id): array
    {
        $apiResponse = $this->client->request('GET', "https://api.themoviedb.org/3/movie/{$id}?&language=fr&api_key={$_ENV['TMDB_API']}");
        return $apiResponse->toArray();
    }

    // Méthode pour la fiche film : récupérer les crédits d'un film à partir de son id
    public function getFilmCredits(string $id): array
    {
        $apiResponse = $this->client->request('GET', "https://api.themoviedb.org/3/movie/{$id}/credits?language=fr&api_key={$_ENV['TMDB_API']}");
        return $apiResponse->toArray();
    }

    // Méthode pour la fiche film : récupérer les services de VOD et SVOD sur lesquels le film est disponible, à partir de l'id du film
    public function getFilmProviders(string $id): array
    {
        $apiResponse = $this->client->request('GET', "https://api.themoviedb.org/3/movie/{$id}/watch/providers?language=fr&api_key={$_ENV['TMDB_API']}");
        return $apiResponse->toArray();
    }

    // Méthode pour récupérer le titre des films 
    public function getFilmTitle($filmId): string
    {
        if ($filmId !== null) {
            $filmDetails = $this->getFilmDetails($filmId);
            return $filmDetails['title'] ?? 'Titre non disponible';
        } else {
            return 'Titre non disponible';
        }
    }

    // Méthode pour afficher les films populaires

    // Méthode pour rechercher une liste de films par noms de personnes
}

    //Ajouter la requête pour récupérer les images à partir de l'id d'un films