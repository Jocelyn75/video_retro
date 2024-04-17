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

    public function searchFilms(string $searchQuery): array
    {
        $apiResponse = $this->client->request('GET', "https://api.themoviedb.org/3/search/movie?query={$searchQuery}&language=fr&api_key={$_ENV['TMDB_API']}");
        $apiResponseArray = $apiResponse->toArray();
        return $apiResponseArray['results'];
    }

    public function getFilmDetails(string $id): array
    {
        $apiResponse = $this->client->request('GET', "https://api.themoviedb.org/3/movie/{$id}?&language=fr&api_key={$_ENV['TMDB_API']}");
        return $apiResponse->toArray();
    }

    public function getFilmCredits(string $id): array
    {
        $apiResponse = $this->client->request('GET', "https://api.themoviedb.org/3/movie/{$id}/credits?language=fr&api_key={$_ENV['TMDB_API']}");
        return $apiResponse->toArray();
    }

    public function getFilmProviders(string $id): array
    {
        $apiResponse = $this->client->request('GET', "https://api.themoviedb.org/3/movie/{$id}/watch/providers?language=fr&api_key={$_ENV['TMDB_API']}");
        return $apiResponse->toArray();
    }
}