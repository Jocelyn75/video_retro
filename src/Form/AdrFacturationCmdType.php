<?php

namespace App\Form;

use App\Entity\AdrFacturationCmd;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AdrFacturationCmdType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('adr_fact_cmd', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir une adresse de facturation'
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AdrFacturationCmd::class,
        ]);
    }
}
