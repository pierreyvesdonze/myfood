<?php

use App\Entity\RecipeIngredient;

class Functions
{
    public function convertUnits(RecipeIngredient $recipeIngredient) {
        $recipeIngredient->setAmount(100000);

        return $recipeIngredient;
    }
}