<?php

namespace App\Form\Type;

use App\Entity\Ingredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IngredientType extends AbstractType
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
            'quantity',
            IntegerType::class,
            [
                "label" => "Quantité d'ingrédient"
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
            'data_class' => Ingredient::class,
            'attr' => [
                'novalidate' => 'novalidate'
            ],
        ]);
    }
}