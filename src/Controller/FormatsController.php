<?php

namespace App\Controller;

use App\Entity\Formats;
use App\Form\FormatsType;
use App\Repository\FormatsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/formats')]
class FormatsController extends AbstractController
{
    #[Route('/', name: 'app_formats_index', methods: ['GET'])]
    public function index(FormatsRepository $formatsRepository): Response
    {
        return $this->render('formats/index.html.twig', [
            'formats' => $formatsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_formats_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $format = new Formats();
        $form = $this->createForm(FormatsType::class, $format);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($format);
            $entityManager->flush();

            return $this->redirectToRoute('app_formats_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('formats/new.html.twig', [
            'format' => $format,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_formats_show', methods: ['GET'])]
    public function show(Formats $format): Response
    {
        return $this->render('formats/show.html.twig', [
            'format' => $format,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_formats_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Formats $format, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FormatsType::class, $format);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_formats_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('formats/edit.html.twig', [
            'format' => $format,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_formats_delete', methods: ['POST'])]
    public function delete(Request $request, Formats $format, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$format->getId(), $request->request->get('_token'))) {
            $entityManager->remove($format);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_formats_index', [], Response::HTTP_SEE_OTHER);
    }
}
