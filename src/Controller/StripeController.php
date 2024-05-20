<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use Stripe\Charge;
use Stripe\Stripe;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use App\Entity\Commandes;
use App\Service\TMDBService;
use App\Entity\AdrLivraisonCmd;
use App\Entity\AdrLivraisonUser;
use App\Entity\DetailsCommandes;
use App\Entity\AdrFacturationCmd;
use App\Entity\AdrFacturationUser;
use App\Repository\StockRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\CommandeRefGeneratorService;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\AdrLivraisonUserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
        //Méthode à utiliser s'il n'y a pas de frais de livraison.
        // $montant = 0;
        // $cart = $this->session->get('cart', []);

        // foreach($cart as $idStock => $quantite){
        //     $stock = $this->stockRepository->find($idStock);
        //     $montant += ($stock->getPrixReventeDefaut() * 100) * $quantite;
        // }
        
        //Uniquement besoin de ce return pour récupérer le montant total de la commande dans le panier.  
        // Si la clé n'est pas trouvée dans la session, la méthode renverra 0
        return $this->session->get('montantTotalCommande', 0) *100;
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

            $stripeId = $charge->id;
            $this->session->set('stripeId', $stripeId);

            return $this->redirectToRoute('payment_success');
        
        } catch (\Exception $e) {
            return $this->redirectToRoute('payment_failure');
        }
    }

    #[Route('/payment-success', name: 'payment_success')]    
    public function payment_success(Request $request, EntityManagerInterface $em, SessionInterface $session, StockRepository $stockRepository, TMDBService $tmdbService, CommandeRefGeneratorService $commandeRef, AdrLivraisonUserRepository $adrLivraisonUserRepository, AdrLivraisonUser $adrLivraisonUser, AdrFacturationCmd $adrFacturationCmd, AdrFacturationUser $adrFacturationUser, AdrLivraisonCmd $adrLivraisonCmd, MailerInterface $mailer):Response
    {

            $user = $this->getUser();
            $cart = $session->get('cart', []);
            $montantTotalCommande = $this->getMontantTotal();
            $livreur = $this->session->get('livreur');
            $adrLivraisonUser = $session->get('adrLivraisonUser');
            $adrFacturationUser = $user->getAdrFacturationUser();
            if (!$adrFacturationUser) {
                throw $this->createNotFoundException('Aucune adresse de facturation pour cet utilisateur.');
            }

            $adrLivraisonCmd = new AdrLivraisonCmd();
            $adrLivraisonCmd->setNom($adrLivraisonUser->getNom());
            $adrLivraisonCmd->setPrenom($adrLivraisonUser->getPrenom());
            $adrLivraisonCmd->setAdrLivrCmd($adrLivraisonUser->getAdresse());
            $adrLivraisonCmd->setComplementAdr($adrLivraisonUser->getComplementAdr());
            $adrLivraisonCmd->setCodePostal($adrLivraisonUser->getCodePostal());
            $adrLivraisonCmd->setVille($adrLivraisonUser->getVille());
            $em->persist($adrLivraisonCmd);

            $adrFacturationCmd = new AdrFacturationCmd();
            $adrFacturationCmd->setNom($adrFacturationUser->getNom());
            $adrFacturationCmd->setPrenom($adrFacturationUser->getPrenom());
            $adrFacturationCmd->setAdrFactCmd($adrFacturationUser->getAdresse());
            $adrFacturationCmd->setComplementAdr($adrFacturationUser->getComplementAdr());
            $adrFacturationCmd->setCodePostal($adrFacturationUser->getCodePostal());
            $adrFacturationCmd->setVille($adrFacturationUser->getVille());
            $em->persist($adrFacturationCmd);
    
            // On crée une nouvelle commande
            $commande = new Commandes();
            // On remplit la commande
            $commande->setUser($this->getUser());
            $commande->setCreatedAt(new \DateTimeImmutable('now'));
            $commande->setReference($commandeRef->generateReference());
            $commande->setMontantTotal($montantTotalCommande);
            $commande->setAdrLivraisonCmd($adrLivraisonCmd);
            $commande->setAdrFacturationCmd($adrFacturationCmd);
            $commande->getLivreur($livreur);
            $stripeId = $session->get('stripeId');
            $commande->setStripeId($stripeId);
            $em->persist($commande);

            $adrFacturationCmd->setCommandes($commande);
            $adrLivraisonCmd->setCommandes($commande);

            //On crée un tableau pour stocker les détails commande et pouvoir les passer à la vue Twig du pdf.
            $detailsCommandes = [];
            //On boucle le panier pour créer les détails de la commande.
            foreach ($cart as $stockId => $quantity) {
                $detailCommande = new DetailsCommandes();
                $detailCommande->setCommandes($commande);
                // On va chercher le produit (avec la requête API pour obtenir le titre).
                $stock = $stockRepository->find($stockId);
                $filmId = $stock->getFilms()->getFilmsApiId();
                $stock->titre = $tmdbService->getFilmTitle($filmId);
                $prix = $stock->getPrixReventeDefaut();
    
                //On crée le détails de commande
                $detailCommande->setStockId($stockId);
                $detailCommande->setPrixUnitaire($prix);
                $detailCommande->setQuantiteCmd($quantity);
                $em->persist($detailCommande);

                // On ajoute les détails de la commande au tableau
                $detailsCommandes[] = $detailCommande;


                //On met à jour la quantité en stock.
                $stock->setQuantiteStock($stock->getQuantiteStock() - $quantity);
                $em->persist($stock);
            }


            $em->flush();
            $session->remove('cart');
            // ---------------
            // Persist et flush à faire à la validation de la commande par l'utilisateur pour ne pas injecter les données de la commande en BDD tant que la commande n'est pas validée. 
            // Avec persist on crée les requêtes.
            // $em->persist($commande);
            // // Avec flush, on les exécutes
            // $em->flush();
            // $session->remove('cart');
            // ---------------            
            
        //new Commande, new DetailsCommande
        //Soustraire les stocks (flush)
        //vider le panier


        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsHtml5ParserEnabled(true);

        $dompdf = new Dompdf($pdfOptions); 
        $dompdf->set_option("enable_php", true);

        $html = $this->renderView('pdf/fiche_commande.html.twig', [
            'commande' => $commande,
            'detailsCommandes' => $detailsCommandes,
            'montantTotalCommande' => $montantTotalCommande
        ]);

        // Charger le HTML dans Dompdf
        $dompdf->loadHtml($html);
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');
        // Rendre le HTML au format PDF
        $dompdf->render();

        // Sortie du PDF généré dans le navigateur (téléchargement forcé)
        $pdf = $dompdf->output();

        $fichier =  $commande->getReference() . '.pdf';

        $location = $this->getParameter('private') . '/' . $fichier;
        file_put_contents($location, $pdf);

        $email = (new Email())
        ->from(new Address('no-reply@videoretro.com', 'Video Retro'))
        ->to($user->getEmail())
        ->subject('Merci pour votre commande sur Video Retro')
        ->html('<h1>Merci pour votre commande sur Video Retro ! </h1>
                <p> Tous les détails sont à retrouver dans votre facture ci-jointe</p>
                ')
        ->attachFromPath($location);

        $mailer->send($email);

        $this->addFlash('message', 'Commande créée avec succès');
        return $this->render('stripe/payment_success.html.twig', [
        'commande' => $commande,
        'cart' => $cart,
        'detailsCommandes' => $detailsCommandes,
        'montantTotalCommande' => $montantTotalCommande
        ]);
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
