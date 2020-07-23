<?php

namespace App\Form\Type;

use App\Entity\RecipeIngredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchIngredientType extends AbstractType
{
    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options)
    {
        $builder->add('name',TextType::class,
            [
                'label' => "Nom de l'ingrédient",
                'attr' => [
                    'class' => "search-by-ingredient-input"
                ]
            ],
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RecipeIngredient::class,
            'attr' => [
                'novalidate' => 'novalidate',
            ],
        ]);
    }
}
