<?php

namespace App\Form\Type;

use App\Entity\Recipe;
use App\Entity\MealPlan;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MealPlanType extends AbstractType
{
    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'name',
            TextType::class,
            [
                'label' => 'Titre du plan hebdomadaire'
            ]
        );

        $builder->add(
            'recipes',
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
                'label' => 'Date'
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MealPlan::class,
            'attr' => [
                'novalidate' => 'novalidate',
            ],
        ]);
    }
}
