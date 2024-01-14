<?php

namespace App\Form;

use App\Entity\DetailsCommandes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DetailsCommandesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('commandes_id')
            ->add('stock_id')
            ->add('quantite_cmd')
            ->add('prix_unitaire')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DetailsCommandes::class,
        ]);
    }
}
