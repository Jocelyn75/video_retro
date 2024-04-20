<?php

namespace App\Controller;

use App\Entity\AdrFacturationCmd;
use App\Form\AdrFacturationCmdType;
use App\Repository\AdrFacturationCmdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/adr/facturation/cmd')]
class AdrFacturationCmdController extends AbstractController
{
    #[Route('/', name: 'app_adr_facturation_cmd_index', methods: ['GET'])]
    public function index(AdrFacturationCmdRepository $adrFacturationCmdRepository): Response
    {
        return $this->render('adr_facturation_cmd/index.html.twig', [
            'adr_facturation_cmds' => $adrFacturationCmdRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_adr_facturation_cmd_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $adrFacturationCmd = new AdrFacturationCmd();
        $form = $this->createForm(AdrFacturationCmdType::class, $adrFacturationCmd);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($adrFacturationCmd);
            $entityManager->flush();

            return $this->redirectToRoute('app_adr_facturation_cmd_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('adr_facturation_cmd/new.html.twig', [
            'adr_facturation_cmd' => $adrFacturationCmd,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_adr_facturation_cmd_show', methods: ['GET'])]
    public function show(AdrFacturationCmd $adrFacturationCmd): Response
    {
        return $this->render('adr_facturation_cmd/show.html.twig', [
            'adr_facturation_cmd' => $adrFacturationCmd,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_adr_facturation_cmd_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AdrFacturationCmd $adrFacturationCmd, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdrFacturationCmdType::class, $adrFacturationCmd);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_adr_facturation_cmd_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('adr_facturation_cmd/edit.html.twig', [
            'adr_facturation_cmd' => $adrFacturationCmd,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_adr_facturation_cmd_delete', methods: ['POST'])]
    public function delete(Request $request, AdrFacturationCmd $adrFacturationCmd, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$adrFacturationCmd->getId(), $request->request->get('_token'))) {
            $entityManager->remove($adrFacturationCmd);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_adr_facturation_cmd_index', [], Response::HTTP_SEE_OTHER);
    }
}
