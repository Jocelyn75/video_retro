<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class TMDBService
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    // Requête pour la barre de recherche : recherche d'un film par mots-clés.
    public function searchFilms(string $searchQuery): array
    {
        $apiResponse = $this->client->request('GET', "https://api.themoviedb.org/3/search/movie?query={$searchQuery}&language=fr&api_key={$_ENV['TMDB_API']}");
        $apiResponseArray = $apiResponse->toArray();
        return $apiResponseArray['results'];
    }

    // Requête pour la fiche film : récupération des données d'un film à partir de son id
    public function getFilmDetails(string $id): array
    {
        $apiResponse = $this->client->request('GET', "https://api.themoviedb.org/3/movie/{$id}?&language=fr&api_key={$_ENV['TMDB_API']}");
        return $apiResponse->toArray();
    }

    // Requête pour la fiche film : récupérer les crédits d'un film à partir de son id
    public function getFilmCredits(string $id): array
    {
        $apiResponse = $this->client->request('GET', "https://api.themoviedb.org/3/movie/{$id}/credits?language=fr&api_key={$_ENV['TMDB_API']}");
        return $apiResponse->toArray();
    }

    // Requête pour la fiche film : récupérer les services de VOD et SVOD sur lesquels le film est disponible, à partir de l'id du film
    public function getFilmProviders(string $id): array
    {
        $apiResponse = $this->client->request('GET', "https://api.themoviedb.org/3/movie/{$id}/watch/providers?language=fr&api_key={$_ENV['TMDB_API']}");
        return $apiResponse->toArray();
    }
}