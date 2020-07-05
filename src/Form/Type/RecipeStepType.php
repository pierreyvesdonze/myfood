<?php

namespace App\Form\Type;

use App\Entity\RecipeStep;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeStepType extends AbstractType
{
    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options)
    {

        $builder->add(
            'text',
            TextType::class,
            [
                "label" => "Description de l'étape"
            ]
        );

        $builder->add(
            'photo',
            TextType::class,
            [
                "label" => "Ajouter une photo"
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RecipeStep::class,
            'attr' => [
                'novalidate' => 'novalidate'
            ],
        ]);
    }
}
