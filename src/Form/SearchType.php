<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType as TypeSearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        if ($options['keyword']) {
            $builder->add('keyword', TypeSearchType::class, [
                'required' => false,
            ]);
        }

        if ($options['year']) {
            $builder->add('year', TypeSearchType::class, [
                'required' => false,
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'keyword' => false,
            'year' => false,
        ]);
    }
}
