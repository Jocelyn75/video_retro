<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\AdrLivraisonUser;
use App\Entity\AdrFacturationUser;
use Symfony\Component\Form\AbstractType;
use phpDocumentor\Reflection\Types\Boolean;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', TextType::class, [
            'constraints' => [
                new Email([
                    'message' => "Veuillez saisir une adresse mail valide" // Permet de personnaliser le message d'erreur si l'email entré par l'utilisateur est invalide.
                ])
            ]
        ])

        ->add('nom', TextType::class, [
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez saisir votre nom de famille',
                ])
            ]
        ])
        ->add('prenom', TextType::class, [
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez saisir votre prénom',
                ])
            ]
        ])
        
        ->add('date_naiss', BirthdayType::class, [
            'widget' => 'single_text',        
        ])
        ->add('tel_user', TextType::class, [
        ])

            
        // ->add('adr_user', TextType::class)

        // ->add('complement_adr')

        // ->add('code_postal', NumberType::class, [
        //     'constraints' => [
        //         new Positive ([
        //             'message' => 'Le code postal doit être un nombre positif',
        //         ]),
        //         new Length ([
        //             'min' => 5,
        //             'max' => 5,
        //             'minMessage' => 'Un code postal doit comprendre 5 chiffres',
        //             'maxMessage' => 'Un code postal doit comprendre 5 chiffres',
        //         ])
        //     ]
        // ])

        // ->add('ville', TextType::class)

        ;
    }

        // $builder
        //     ->add('email', TextType::class)
        //     // ->add('roles')
        //     // ->add('password')
        //     ->add('nom', TextType::class)
        //     ->add('prenom', TextType::class)
        //     ->add('date_naiss', BirthdayType::class)
        //     ->add('adr_user', TextType::class)
        //     ->add('complement_adr', TextType::class)
        //     ->add('code_postal', IntegerType::class)
        //     ->add('ville', TextType::class)
        //     ->add('tel_user', TextType::class)
        //     // ->add('cagnotte')
        //     // ->add('activation')
        //     ->add('adr_facturation_user', EntityType::class, [
        //         'class' => AdrFacturationUser::class,
        //         'choice_label' => 'id',
        //     ])
        //     ->add('adr_livraison_user', EntityType::class, [
        //         'class' => AdrLivraisonUser::class,
        //         'choice_label' => 'id',
        //         'multiple' => true,
        //     ])
        // ;

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
