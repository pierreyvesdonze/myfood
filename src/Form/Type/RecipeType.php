<?php

namespace App\Form\Type;

use App\Entity\Recipe;
use App\Entity\RecipeCategory;
use App\Entity\RecipeMenu;
use App\Entity\Tag;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class RecipeType extends AbstractType
{
    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'name',
            TextType::class,
            [
                'label' => 'Nom de la recette',
                'required' => true,
            ]
        );

        $builder->add('recipeSteps', CollectionType::class, [
            'entry_type' => RecipeStepType::class,
            'entry_options' => ['label' => false],
            'allow_add' => true,
            'by_reference' => false,
            'allow_delete' => true,
            'required' => true,
        ]);

        $builder->add('recipeIngredients', CollectionType::class, [
            'entry_type' => RecipeIngredientType::class,
            'entry_options' => ['label' => false],
            'required' => true,
            'allow_add' => true,
            'by_reference' => false,
            'allow_delete' => true,
            'required' => true,
        ]);

        $builder->add('person', IntegerType::class, [
            'label' => 'Nb de personnes',
            'required' => true,
            'attr' => [
                'min' => 1,
                'max' => 12,
            ],
        ]);

        $builder->add('timePrepa', TimeType::class, [
            'label' => 'Temps de préparation',
            'input' => 'datetime',
            'widget' => 'choice',
            'html5' => 'true',
        ]);

        $builder->add('timeCook', TimeType::class, [
            'label' => 'Temps de cuisson',
            'input' => 'datetime',
            'widget' => 'choice',
            'html5' => 'true',
        ]);

        $builder->add(
            'description',
            TextareaType::class,
            [
                'label' => 'Description de la recette',
            ]
        );

        $builder->add('recipePhoto', FileType::class, [
            'label' => 'Ajouter une image',
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new File([
                    'mimeTypes' => [
                        'image/jpeg',
                    ],
                ]),
            ],
            'attr' => [
                'class' => 'filename'
            ]
        ]);

        $builder->add('recipeCategory', EntityType::class, [
            'class' => RecipeCategory::class,
            'choice_label' => 'name',
            'label' => false
        ]);

        $builder->add('recipeMenu', EntityType::class, [
            'class' => RecipeMenu::class,
            'choice_label' => 'name',
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
                'label' => 'Valider',
                'attr' => [
                    'class' => 'button-save',
                ],
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
            'mapped' => false,

        ]);
    }
}
