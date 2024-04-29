<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cart')]
class CartController extends AbstractController
{
    #[Route('/', name: 'app_cart')]
    public function index(SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);
        dump($cart);
        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
        ]);
    }

    #[Route('/add', name: 'app_cart_new', methods: ['POST'])]
    public function add(Request $request, SessionInterface $session)
    {
        $stockId = $request->request->get('stock');
        $quantite = $request->request->get('quantite');

        // dump($stockId);
        // dd($quantite);

        $cart = $session->get('cart', []);

        if (isset($cart[$stockId])) {
            $cart[$stockId] += $quantite;
        }else{
            $cart[$stockId] = $quantite;
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('app_cart');

        // $panier = [
        //     7 => 3,
        //     9 => 12,
        //     15 => 4
        // ]
        // Key : id du produit
        // Value : quantité

    }

}
