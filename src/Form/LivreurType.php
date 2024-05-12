<?php

namespace App\Form;

use App\Entity\Livreur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class LivreurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_livreur', TextType::class, [
                'label' => 'Nom du transporteur',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer le nom d\'un transporteur',
                    ])
                ]
            ])
            ->add('prix', IntegerType::class, [
                'label' => 'Prix (€)',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un prix',
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livreur::class,
        ]);
    }
}
