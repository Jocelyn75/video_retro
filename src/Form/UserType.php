<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', TextType::class, [
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez saisir une adresse mail',
                ]),
                new Email([
                    'message' => "Veuillez saisir une adresse mail valide" // Permet de personnaliser le message d'erreur si l'email entré par l'utilisateur est invalide.
                ])
            ]
        ])
        ->add('nom', TextType::class, [
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez saisir un nom de famille',
                ])
            ]
        ])
        ->add('prenom', TextType::class, [
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez saisir un prénom',
                ])
            ]
        ])
        ->add('roles', ChoiceType::class, [
                'choices'=>[
                    'administrateur'=>'ROLE_ADMIN'
                ],
                'multiple'=> true,
                'expanded' => true
            ])

        ->add('date_naiss', DateType::class)
            // ->add('password')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
