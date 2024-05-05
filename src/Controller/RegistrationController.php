<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\CodeManager;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]    
    /**
     * register
     *
     * @param  mixed $request
     * @param  mixed $userPasswordHasher
     * @param  mixed $entityManager
     * @param  mixed $codeManager
     * @param  mixed $mailer
     * @return Response
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, CodeManager $codeManager, MailerInterface $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $user->setActivation($codeManager->getCode());
            $entityManager->persist($user);
            $entityManager->flush();
            $email = (new TemplatedEmail())
                ->from(new Address('noreply@videoretro.fr', 'Video Retro'))
                ->to($user->getEmail())
                ->subject('Réinitialisation de votre mot de passe')
                ->htmlTemplate('email/inscription.html.twig')
                ->context([
                    'user' => $user,
                ])
            ;

        $mailer->send($email);
        $this->addFlash('success', "Bienvenue, un email vous a été envoyé pour valider votre compte.");
        return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/activation/{token})', name: 'activation')]    
    /**
     * activation
     *
     * @param  mixed $token
     * @param  mixed $userRepository
     * @param  mixed $entityManager
     * @return void
     */
    public function activation($token, UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $user = $userRepository->findOneBy(['activation' => $token]);

        if (!$user) {
            throw $this->createNotFoundException('Cet utilisateur n\'existe pas');
        }
        $user->setActivation(NULL);
        $entityManager->flush();
        $this->addFlash('success', "Votre compte est activé");
        return $this->redirectToRoute('app_login');
    }
}
