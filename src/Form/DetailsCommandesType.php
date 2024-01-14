<?php

namespace App\Form;

use App\Entity\DetailsCommandes;
use Doctrine\DBAL\Types\FloatType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class DetailsCommandesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('commandes_id', IntegerType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un identifiant de commande',
                    ])
                ]
            ])
            ->add('stock_id', IntegerType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez indiquer l\'identifiant du produit',
                    ])
                ]
            ])
            ->add('quantite_cmd', IntegerType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez indiquer la quantité commandée',
                    ])
                ]
            ])
            ->add('prix_unitaire', FloatType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez indiquer le prix unitaire',
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DetailsCommandes::class,
        ]);
    }
}
