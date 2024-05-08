<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class EditPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('currentPassword', PasswordType::class, [
                'required' => false,
                'mapped' => false,
                'label' => 'Mot de passe actuel<span class=\'text-danger\'>*</span>',
                'label_html'=> true,
                'attr' => [
                    'class' => 'password-field-alone'
                ]
            ])

            ->add('newPassword', PasswordType::class, [
                'required' => false,
                'mapped' => false,
                'label' => 'Nouveau mot de passe<span class=\'text-danger\'>*</span>',
                'label_html'=> true,
                'attr' => [
                    'class' => 'password-field-first'
                ],
                'label_attr' => [
                    'class' => 'd-inline'
                ]
            ])

            ->add('confirmPassword', PasswordType::class, [
                'required' => false,
                'mapped' => false,
                'label' => 'Confirmation du nouveau mot de passe<span class=\'text-danger\'>*</span>',
                'label_html'=> true,
                'attr' => [
                    'class' => 'password-field-second'
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


        // ->add('plainPassword', RepeatedType::class, [
        //     'type' => PasswordType::class,
        //     'mapped' => false,
        //     'attr' => ['autocomplete' => 'new-password'],
        //     'first_options'  => ['label' => 'Mot de passe'],
        //     'second_options' => ['label' => 'Confirmez votre mot de passe'],
        //     'invalid_message' => 'Les mots de passe ne correspondent pas',
        //     'constraints' => [
        //     new NotBlank([
        //         'message' => 'Veuillez renseigner un mot de passe',
        //     ]),

        //     new Assert\Regex([
        //         'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W])[A-Za-z\d\W]{12,255}$/',
        //         'message' => 'Votre mot de passe doit être composé d\'au moins 12 caractères et contenir au moins 1 chiffre, 1 majuscule, 1 minuscule et 1 caractère spécial.',
        //     ]),

        //     new Assert\NotCompromisedPassword([
        //         'message' => "Avertissement : ce mot de passe est recensé dans des fuites de données en ligne. Veuillez en choisir un autre pour protéger votre compte. "
        //     ])
        //     ],
        // ])

        // ->add('newPassword', PasswordType::class, [
        // 'label' => "Nouveau mot de passe",
        // 'constraints' => [
        //     new NotBlank([
        //         'message' => 'Veuillez renseigner un mot de passe',
        //     ]),

        //     new Assert\Regex([
        //         'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W])[A-Za-z\d\W]{12,255}$/',
        //         'message' => 'Votre mot de passe doit être composé d\'au moins 12 caractères et contenir au moins 1 chiffre, 1 majuscule, 1 minuscule et 1 caractère spécial.',
        //     ]),

        //     new Assert\NotCompromisedPassword([
        //         'message' => "Ce mot de passe est faible"
        //     ])
        //     ],
        
        // ]);



            // ->add('plainPassword')
            // ->add('newPassword', RepeatedType::class, [
            // 'type' => PasswordType::class,
            // 'mapped' => false,
            // 'attr' => ['autocomplete' => 'new-password'],
            // 'first_options'  => ['label' => 'Mot de passe'],
            // 'second_options' => ['label' => 'Confirmez votre mot de passe'],
            // 'invalid_message' => 'Les mots de passe ne correspondent pas',
            // 'constraints' => [
            //     new NotBlank([
            //         'message' => 'Veuillez renseigner un mot de passe',
            //     ]),

            //     new Assert\Regex([
            //         'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W])[A-Za-z\d\W]{12,255}$/',
            //         'message' => 'Votre mot de passe doit être composé d\'au moins 12 caractères et contenir au moins 1 chiffre, 1 majuscule, 1 minuscule et 1 caractère spécial.',
            //     ]),

            //     new Assert\NotCompromisedPassword([
            //         'message' => "Ce mot de passe est faible"
            //     ])
            // ],
            // ])




            //             ->add('email')
            //             ->add('roles')
            //             ->add('password')
            //             ->add('nom')
            //             ->add('prenom')
            //             ->add('date_naiss')
            //             ->add('adr_user')
            //             ->add('complement_adr')
            //             ->add('code_postal')
            //             ->add('ville')
            //             ->add('tel_user')
            //             ->add('cagnotte')
            //             ->add('activation')
            //             ->add('adr_facturation_user', EntityType::class, [
            //                 'class' => AdrFacturationUser::class,
            // 'choice_label' => 'id',
            //             ])
            //             ->add('adr_livraison_user', EntityType::class, [
            //                 'class' => AdrLivraisonUser::class,
            // 'choice_label' => 'id',
            // 'multiple' => true,
            //             ])


