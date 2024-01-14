<?php

namespace App\Controller;

use App\Entity\AdrLivraisonCmd;
use App\Form\AdrLivraisonCmdType;
use App\Repository\AdrLivraisonCmdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/adr/livraison/cmd')]
class AdrLivraisonCmdController extends AbstractController
{
    #[Route('/', name: 'app_adr_livraison_cmd_index', methods: ['GET'])]
    public function index(AdrLivraisonCmdRepository $adrLivraisonCmdRepository): Response
    {
        return $this->render('adr_livraison_cmd/index.html.twig', [
            'adr_livraison_cmds' => $adrLivraisonCmdRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_adr_livraison_cmd_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $adrLivraisonCmd = new AdrLivraisonCmd();
        $form = $this->createForm(AdrLivraisonCmdType::class, $adrLivraisonCmd);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($adrLivraisonCmd);
            $entityManager->flush();

            return $this->redirectToRoute('app_adr_livraison_cmd_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('adr_livraison_cmd/new.html.twig', [
            'adr_livraison_cmd' => $adrLivraisonCmd,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_adr_livraison_cmd_show', methods: ['GET'])]
    public function show(AdrLivraisonCmd $adrLivraisonCmd): Response
    {
        return $this->render('adr_livraison_cmd/show.html.twig', [
            'adr_livraison_cmd' => $adrLivraisonCmd,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_adr_livraison_cmd_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AdrLivraisonCmd $adrLivraisonCmd, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdrLivraisonCmdType::class, $adrLivraisonCmd);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_adr_livraison_cmd_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('adr_livraison_cmd/edit.html.twig', [
            'adr_livraison_cmd' => $adrLivraisonCmd,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_adr_livraison_cmd_delete', methods: ['POST'])]
    public function delete(Request $request, AdrLivraisonCmd $adrLivraisonCmd, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$adrLivraisonCmd->getId(), $request->request->get('_token'))) {
            $entityManager->remove($adrLivraisonCmd);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_adr_livraison_cmd_index', [], Response::HTTP_SEE_OTHER);
    }
}
