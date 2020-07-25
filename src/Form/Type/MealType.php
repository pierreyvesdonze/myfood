<?php

namespace App\Form\Type;

use App\Entity\Meal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class MealType extends AbstractType
{
    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'recipies',
            CollectionType::class,
            [
                'entry_type' => MealPlanRecipeType::class,
                'entry_options' => ['label' => false],
                'by_reference' => false,
                'allow_delete' => true,
                'allow_add' => true,
                'mapped' => false,
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
        'data_class' => Meal::class,
        'attr' => [
            'novalidate' => 'novalidate',
        ],
    ]);
    }
}
