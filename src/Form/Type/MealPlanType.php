<?php

namespace App\Form\Type;

use App\Entity\MealPlan;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
            'meals',
            CollectionType::class,
            [
                'entry_type' => MealType::class,
                'entry_options' => ['label' => false],
                'by_reference' => false,
                'allow_delete' => true,
                'allow_add' => true,
                'mapped' => false,
            ]
        );

        $builder->add(
            'save',
            SubmitType::class,
            [
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'button',
                ],
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
