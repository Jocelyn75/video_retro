<?php

namespace App\Form;

use App\Entity\Commandes;
use Doctrine\DBAL\Types\FloatType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CommandesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user_id')
            ->add('montant_total', FloatType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un montant total',
                    ])
                ]
            ])
            ->add('date_cmd', DateType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir une date de commande',
                    ])
                ]
            ])
            ->add('statut_cmd', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un statut pour cette commande',
                    ])
                ]
            ])
            ->add('stripe_id', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un identifiant de paiement Stripe',
                    ])
                ]
            ])
            ->add('achat_ou_vente', ChoiceType::class, [
                'choices' => [
                    'Achat' => true,
                    'Vente' => false
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez sélectionner une option',
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commandes::class,
        ]);
    }
}
