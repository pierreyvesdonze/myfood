<?php

namespace App\Form\Type;

use App\Entity\ShoppingList;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ShoppingListAddArticleType extends AbstractType
{
    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options)
    {
        $builder->add('shoppingList', EntityType::class, [
            'class' => ShoppingList::class,
            'choice_label' => 'description',
        ]);

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
}
