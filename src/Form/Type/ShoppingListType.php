<?php

namespace App\Form\Type;

use App\Entity\ShoppingList;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ShoppingListType extends AbstractType
{
    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options)
    {
        $builder->add('description', TextType::class, [
                'label' => "Quel nom voulez-vous donner à votre liste de courses ?",
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
