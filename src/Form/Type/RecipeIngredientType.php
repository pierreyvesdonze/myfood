<?php

namespace App\Form\Type;

use App\Entity\RecipeIngredient;
use App\Entity\Unit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
                'label' => "Nom de l'ingrédient *",
                'required' => false,
            ]
        );

        $builder->add(
            'amount',
            NumberType::class,
            [
                'label' => "Quantité d'ingrédient(s) *",
                'scale' => 1,
                'attr' => [
                    'step' => '.5',
                    'html5' => true,
                ],
            ]
        );

        $builder->add(
            'unit',
            EntityType::class,
            [
                'class' => Unit::class,
                'choice_label' => 'name',
                'label' => "Unité de mesure",
            ]
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
