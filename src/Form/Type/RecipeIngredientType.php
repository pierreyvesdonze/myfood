<?php

namespace App\Form\Type;

use App\Entity\RecipeIngredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeIngredientType extends AbstractType
{
    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options)
    {

        $builder->add(
            'name',
            TextType::class,
            [
                "label" => "Nom de l'ingrédient"
            ]
        );

        $builder->add(
            'amount',
            NumberType::class,
            [
                "label" => "Quantité d'ingrédient(s)",
                'scale' => 1,
                'attr' => [
                    'step' => '.5',
                    'html5' => true
                ]
            ]
        );

        $builder->add(
            'unit',
            TextType::class,
            [
                "label" => "Unité (Litre, cl, g, grammes etc.)"
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RecipeIngredient::class,
            'attr' => [
                'novalidate' => 'novalidate'
            ],
        ]);
    }
}
