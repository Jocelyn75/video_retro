<?php

namespace App\Controller;

use Stripe\Charge;
use Stripe\Stripe;
use App\Repository\StockRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class StripeController extends AbstractController
{
    private $stockRepository;
    private $session;
    
    /**
     * __construct
     *
     * @param  mixed $stockRepository
     * @param  mixed $requestStack
     * @return void
     */
    public function __construct(StockRepository $stockRepository, RequestStack $requestStack)
    {
        $this->stockRepository = $stockRepository;
        $this->session = $requestStack->getSession();

    }
    
    /**
     * getMontantTotal permet de récupérer le montant total du panier en centimes.
     *
     * @return float
     */
    public function getMontantTotal(): float
    {
        $montant = 0;
        $cart = $this->session->get('cart', []);

        foreach($cart as $idStock => $quantite){
            $stock = $this->stockRepository->find($idStock);
            $montant += ($stock->getPrixReventeDefaut() * 100) * $quantite;
        }

        return $montant;
    }

    // Formulaire de paiement
    #[Route('/stripe', name: 'app_stripe')]    
    /**
     * index
     *
     * @return Response
     */
    public function index(): Response
    {

        /*
            CB SUCCESS : 4242 4242 4242 4242
            CB FAILURE : 4000 0000 0000 0002
        */

        // Les montants sont indiqués en centimes : 100 = 1 euro
        $montant = $this->getMontantTotal();

        $stripePublicKey = $_ENV['STRIPE_PUBLIC_KEY'];

        return $this->render('stripe/index.html.twig', [
            'stripe_public_key' => $stripePublicKey,
            'montant' => $montant
        ]);
    }

    #[Route('/process-payment', name: 'process_payment')]    
    /**
     * processPayment
     *
     * @param  mixed $request
     * @return void
     */
    public function processPayment(Request $request)
    {
        $stripeSecretKey = $_ENV['STRIPE_SECRET_KEY'];
        Stripe::setApiKey($stripeSecretKey); //Connexion au dashboard
        $token = $request->request->get('stripeToken');
        //Paiemment accepté
        try {
            // La charge correspond à la transaction.
            $charge = Charge::create([ //Méthode static : on ne génère pas un objet pour accéder à la méthode, on l'appelle depuis la classe. 
                'amount' => $this->getMontantTotal(),
                'currency' => 'eur',
                'description' => 'Achat effectué sur Video Retro',
                'source' => $token
            ]);

            // A enregistrer en BDD dans la commande. 
            $stripeId = $charge->id;

            return $this->redirectToRoute('payment_success');
        //Paiement refusé
        } catch (\Exception $e) {
            return $this->redirectToRoute('payment_failure');
        }
    }

    #[Route('/payment-success', name: 'payment_success')]    
    /**
     * payment_success
     *
     * @return Response
     */
    public function payment_success():Response
    {

        //new Commande, new DetailsCommande
        //Soustraire les stocks
        //vider le panier

        return $this->render('stripe/payment_success.html.twig');
    }

    #[Route('/payment-failure', name: 'payment_failure')]    
    /**
     * payment_failure
     *
     * @return Response
     */
    public function payment_failure():Response
    {
        return $this->render('stripe/payment_failure.html.twig');
    }

}
