<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints as Assert;
class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir une adresse mail',
                    ])
                ]
            ])
            
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Accepter les termes et conditions d\'utilisation.',
                    ]),
                ],
            ])

            // La class RepeatedType permet de créer un champ de vérification du mot de passe. 
            ->add('plainPassword', RepeatedType::class, [
                                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'type' => PasswordType::class,
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Répéter votre mot de passe'],
                'invalid_message' => 'Le mot de passe ne correspond pas',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner un mot de passe',
                    ]),
                    new Length([
                        'min' => 12,
                        'minMessage' => 'Votre mot de passe doit comporter au moins 12 caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                        'maxMessage' => 'Votre mot de passe doit comporter au maximum 4096 caractères',
                    ]),

                    new Assert\Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W\_])[A-Za-z\d\W\_]{12,}$/',
                        'message' => 'Votre mot de passe doit comporter au moins 1 chiffre, 1 majuscule, 1 minuscule et 1 caractère spécial.',
                    ]),
                ],
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

            ->add('date_naiss', DateType::class, [
                'widget' => 'choice',
                //'format' => 'dd/MM/yyyy', 
                'years' => range(date('Y') - 120, date('Y')),
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir une date de naissance'
                    ])
                ]
                
            ])
            
            ->add('adr_user', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir une adresse',
                    ])
                ]
            ])
            ->add('complement_adr')

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
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir une ville',
                    ])
                ]
            ])
            ->add('tel_user', IntegerType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un numéro de téléphone',
                    ])
                ]
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}


