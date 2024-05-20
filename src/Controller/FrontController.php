<?php

namespace App\Controller;

use App\Service\TMDBService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FrontController extends AbstractController
{
    #[Route('/', name: 'home')]
        /**
         * popularFilms
         *
         * @param  mixed $tmdbService
         * @return Response
         */
        public function popularFilms(TMDBService $tmdbService): Response
        {
        // Récupérez la liste des dix films les plus populaires
        $popularFilms = $tmdbService->getPopularFilms();
        $classicFilms = $tmdbService->getClassicFilms();
        $imageUrl = $tmdbService->getImageUrl();

        // Initialiser la variable pour les erreurs
        $error = null;

        // Vérifiez s'il y a des erreurs dans les réponses
        // 'error' est défini dans TMDBService.
        if (isset($popularFilms['error'])) {
            $error = $popularFilms['error'];
            $popularFilms = [];
        } else {
            $popularFilms = $popularFilms;
        }

        if (isset($classicFilms['error'])) {
            $error = $classicFilms['error'];
            $classicFilms = [];
        } else {
            $classicFilms = $classicFilms;
        }
        
        return $this->render('/front/index.html.twig', [
            'popularFilms' => $popularFilms,
            'classicFilms' => $classicFilms,
            'imageUrl' => $imageUrl,
            'error' => $error,
        ]);
    }
}
