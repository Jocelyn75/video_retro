<?php

namespace App\Form;

use App\Entity\AdrFacturationUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class AdrFacturationUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('adr_fact_user', TextType::class, [
            //     'constraints' => [
            //         new NotBlank([
            //             'message' => 'Veuillez saisir une adresse de facturation',
            //         ])
            //     ]
            // ])

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
            ->add('adresse', TextType::class, [
                'label' => 'Adresse',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir une adresse',
                    ])
                ]
            ])
            ->add('complement_adr', TextType::class, [
                'label' => 'Complément d\'adresse',
            ])
            ->add('code_postal', NumberType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un code postal',
                    ]),
                    new Positive ([
                        'message' => 'Le code postal doit être un nombre positif',
                    ]),
                    new Length ([
                        'min' => 5,
                        'max' => 5,
                        'minMessage' => 'Un code postal doit comprendre 5 chiffres',
                        'maxMessage' => 'Un code postal doit comprendre 5 chiffres',
                    ])
                ]
            ])
            ->add('ville', TextType::class, [
                'label' => 'Ville',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir une ville',
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AdrFacturationUser::class,
        ]);
    }
}
