<?php

namespace App\Form\Type;

use App\Entity\MealPlan;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

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
            CollectionType::class,
            [
                'entry_type' => MealPlanRecipeType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
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
