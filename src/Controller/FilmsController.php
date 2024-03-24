<?php

namespace App\Controller;

use App\Entity\Films;
use App\Form\FilmsType;
use App\Repository\FilmsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/films')]
class FilmsController extends AbstractController
{
    #[Route('/', name: 'app_films_index', methods: ['GET'])]
    public function index(FilmsRepository $filmsRepository): Response
    {
        return $this->render('films/index.html.twig', [
            'films' => $filmsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_films_new', methods: ['GET', 'POST'])]
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

    // #[Route('/{id}', name: 'app_films_show', methods: ['GET'])]
    // public function show(Films $film): Response
    // {
    //     return $this->render('films/show.html.twig', [
    //         'film' => $film,
    //     ]);
    // }

    #[Route('/{id}', name: 'app_films_show', methods: ['GET'])]
    public function show(string $id, HttpClientInterface $client): Response
    {
        $apiResponse = $client->request('GET', "https://api.themoviedb.org/3/movie/{$id}?&language=fr&api_key={$_ENV['TMDB_API']}");

        $apiResponse2 = $client->request('GET', "https://api.themoviedb.org/3/movie/{$id}/credits?language=fr&api_key={$_ENV['TMDB_API']}");

        $filmsShow = $apiResponse->toArray();
        $credits = $apiResponse2->toArray();

        $imageUrl = 'https://image.tmdb.org/t/p/';

        $director = array_filter($credits['crew'], function ($crewMember) {
            return $crewMember['job'] === 'Director';
        });
        
        $cast = array_slice($credits['cast'], 0, 10);

        return $this->render('films/show.html.twig', [
            'filmsShow' => $filmsShow,
            'imageUrl' => $imageUrl,
            'director' => $director,
            'cast' => $cast
        ]);
    }

    #[Route('/{id}/edit', name: 'app_films_edit', methods: ['GET', 'POST'])]
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

    #[Route('/{id}', name: 'app_films_delete', methods: ['POST'])]
    public function delete(Request $request, Films $film, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$film->getId(), $request->request->get('_token'))) {
            $entityManager->remove($film);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_films_index', [], Response::HTTP_SEE_OTHER);
    }
}


