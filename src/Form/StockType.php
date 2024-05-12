<?php

namespace App\Form;

use App\Entity\Stock;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

class StockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('formats_id', IntegerType::class)
            ->add('prix_revente_defaut', IntegerType::class)
            ->add('quantite_stock', IntegerType::class)
            // ->add('films_id', IntegerType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Stock::class,
        ]);
    }
}

    // {
    //     $builder
    //         ->add('formats_id', IntegerType::class, [
    //             'constraints' => [
    //                 new NotBlank([
    //                     'message' => 'Veuillez saisir un format',
    //                 ])
    //             ]
    //         ])
    //         ->add('prix_revente_defaut', MoneyType::class, [
    //             'constraints' => [
    //                 new NotBlank([
    //                     'message' => 'Veuillez renseigner un prix de revente par défaut',
    //                 ])
    //             ]
    //         ])
    //         ->add('quantite_stock', IntegerType::class, [
    //             'constraints' => [
    //                 new NotBlank([
    //                     'message' => 'Veuillez renseigner la quantité en stock',
    //                 ])
    //             ]
    //         ])
    //         ->add('films_id', IntegerType::class, [
    //             'constraints' => [
    //                 new NotBlank([
    //                     'message' => 'Veuillez renseigner un identifiant de film',
    //                 ])
    //             ]
    //         ])
    //     ;
    // }