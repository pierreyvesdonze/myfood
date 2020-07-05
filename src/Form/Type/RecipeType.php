<?php

namespace App\Form\Type;

use App\Entity\ShoppingList;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShoppingListType extends AbstractType
{
    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'name', TextType::class,
            [
                'label' => 'Nom de la recette'
            ]
        );

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
                "label" => "Commentaires",
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
            'data_class' => ShoppingList::class,
            'attr' => [
                'novalidate' => 'novalidate'
            ],
        ]);
    }
}
