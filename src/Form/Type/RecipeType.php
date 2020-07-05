<?php

namespace App\Form\Type;

use App\Entity\Recipe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeType extends AbstractType
{
    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'name', TextType::class,
            [
                'label' => 'Nom de la recette'
            ]
        );

        $builder->add('recipeSteps', CollectionType::class, [
            'entry_type'    => RecipeStepType::class,
            'entry_options' => ['label' => false],
            'allow_add'     => true,
            'by_reference'  => false,
            'allow_delete'  => true,
        ]);

        $builder->add('ingredients', CollectionType::class, [
            'entry_type'    => IngredientType::class,
            'entry_options' => ['label' => false],
            'allow_add'     => true,
            'by_reference'  => false,
            'allow_delete'  => true,
        ]);

        $builder->add(
            'description',
            TextareaType::class,
            [
                "label" => "Description de la recette",
            ]
        );
       
        $builder->add(
            'save',
            SubmitType::class,
            [
                "label" => "Enregistrer",
                "attr" => [
                    'class' => 'button',
                ]
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
            'attr' => [
                'novalidate' => 'novalidate'
            ],
        ]);
    }
}