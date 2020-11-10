<?php

namespace App\Form\Type;

use App\Entity\RecipeCategory;
use App\Entity\RecipeMenu;
use App\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FiltersSearchRecipeType extends AbstractType
{
    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options)
    {
        $builder->add('recipeMenu', EntityType::class, [
            'class' => RecipeMenu::class,
            'choice_label' => 'name',
            'required' => false,
            'label' => false
        ]);

        $builder->add('recipeCategory', EntityType::class, [
            'class' => RecipeCategory::class,
            'choice_label' => 'name',
            'required' => false,
            'label' => false
        ]);

        $builder->add('tags', EntityType::class, [
            'class' => Tag::class,
            'expanded' => 'true',
            'multiple' => true,
            'label_attr' => ['class' => 'custom-switch'],
            'label' => false,
            'required' => false,
        ]);

        $builder->add(
            'save',
            SubmitType::class,
            [
                'label' => 'Filtrer',
                'attr' => [
                    'class' => 'button submit-search',
                ],
            ]
        );
    }
}
