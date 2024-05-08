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

        $imageUrl = $tmdbService->getImageUrl();


        // Vous pouvez maintenant passer cette liste à votre template Twig
        return $this->render('/front/index.html.twig', [
            'popularFilms' => $popularFilms,
            'imageUrl' => $imageUrl,
        ]);
    }
}
