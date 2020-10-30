<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'text',
            TextType::class,
            [
                'label' => false,
              
                'attr' => [
                    'class'       => 'search-input',
                    'placeholder' => 'Rechercher',
                ]
            ],
        );

        $builder->add(
            'save',
            SubmitType::class,
            [
                'label' => 'Filtrer',
                'attr' => [
                    'class' => 'main-button-search'
                ],
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
        ]);
    }
}
