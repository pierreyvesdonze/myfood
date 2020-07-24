<?php

namespace App\Form\Type;

use App\Entity\Recipe;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class MealPlanRecipeType extends AbstractType
{
    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'name',
            EntityType::class,
            [
                'class' => Recipe::class,
                'choice_label' => 'name',
            ]
        );

        $builder->add(
            'date',
            DateType::class,
            [
                'widget' => 'choice',
                'input'  => 'datetime_immutable',
                'by_reference' => true,
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
        'data_class' => Recipe::class,
        'attr' => [
            'novalidate' => 'novalidate',
        ],
    ]);
    }
}