<?php

namespace App\Form;

use App\Entity\Formats;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

class FormatsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_format', ChoiceType::class, [
                'choices' => [
                    'VHS' => 'VHS',
                    'DVD' => 'DVD',
                    'BLU-RAY' => 'BLU-RAY'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez sélectionner un format',
                    ])
                ]
            ])
            ->add('prix_rachat_defaut', MoneyType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner un prix de rachat par défaut',
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formats::class,
        ]);
    }
}
