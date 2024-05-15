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
                'language' => 'fr-FR',
                'region' => 'FR',
                'page' => 1,
                ]
            ]);
        $apiResponseArray = $apiResponse->toArray();
        return $apiResponseArray['results'];
    }

    //Méthode pour récupérer la liste des films populaires par année
    public function getPopularFilmsByYear($year): array
    {
        $apiResponse = $this->client->request('GET', 'https://api.themoviedb.org/3/discover/movie', [
            'query' => [
                'api_key' => $_ENV['TMDB_API'],
                'release_date.gte' => "{$year}-01-01",
                'release_date.lte' => "{$year}-12-31",
                'language' => 'fr-FR',
                'region' => 'FR',
                'sort_by' => 'vote_count.desc', 
                'page' => 1,
            ],
        ]);

        $apiResponseArray = $apiResponse->toArray();
        return $apiResponseArray['results'];
    }

    // Méthode pour récupérer les films populaires au moment de la requête
    public function getPopularFilms(): array
    {
        $apiResponse = $this->client->request('GET', 'https://api.themoviedb.org/3/movie/popular', [
            'query' => [
                'api_key' => $_ENV['TMDB_API'],
                'language' => 'fr-FR',
                'region' => 'FR',
                'page' => 1,
            ],
        ]);

        $apiResponseArray = $apiResponse->toArray();
        // Limiter les résultats aux 10 premiers films
        $popularFilms = array_slice($apiResponseArray['results'], 0, 10);
        return $popularFilms;
    }

    public function getClassicFilms(): array
    {
        $apiResponse = $this->client->request('GET', 'https://api.themoviedb.org/3/discover/movie', [
            'query' => [
                'api_key' => $_ENV['TMDB_API'],
                'language' => 'fr-FR',
                'primary_release_date.gte' => '1980-01-01',
                'primary_release_date.lte' => '1999-12-31',
                'region' => 'FR',
                'page' => 1,
            ],
        ]);

        $apiResponseArray = $apiResponse->toArray();
        $classicFilms = array_slice($apiResponseArray['results'], 1, 10);
        return $classicFilms;

    }

    // Méthode pour la fiche film : récupération des données d'un film à partir de son id
    public function getFilmDetails(string $id): array
    {
        $apiResponse = $this->client->request('GET', "https://api.themoviedb.org/3/movie/{$id}?&language=fr-FR&region=FR&api_key={$_ENV['TMDB_API']}");
        return $apiResponse->toArray();
    }

    // Méthode pour la fiche film : récupérer les crédits d'un film à partir de son id
    public function getFilmCredits(string $id): array
    {
        $apiResponse = $this->client->request('GET', "https://api.themoviedb.org/3/movie/{$id}/credits?language=fr-FR&region=FR&api_key={$_ENV['TMDB_API']}");
        return $apiResponse->toArray();
    }

    // Méthode pour la fiche film : récupérer les services de VOD et SVOD sur lesquels le film est disponible, à partir de l'id du film
    public function getFilmProviders(string $id): array
    {
        $apiResponse = $this->client->request('GET', "https://api.themoviedb.org/3/movie/{$id}/watch/providers?language=fr-FR&region=FR&api_key={$_ENV['TMDB_API']}");
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