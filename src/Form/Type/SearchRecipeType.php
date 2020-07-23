<?php

namespace App\Form\Type;

use App\Entity\Ingredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchRecipeType extends AbstractType
{
    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options)
    {
        $builder->add('ingredient', CollectionType::class, [
            'entry_type'    => SearchIngredientType::class,
            'entry_options' => ['label' => false],
            'allow_add'     => true,
            'by_reference'  => false,
            'allow_delete'  => true,
        ]);
        $builder->add(
            'save',
            SubmitType::class,
            [
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'button submit-search',
                ],
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }
}
