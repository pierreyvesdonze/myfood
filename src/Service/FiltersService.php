<?php

namespace App\Service;

use App\Form\Type\FiltersSearchRecipeType;
use App\Repository\RecipeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FiltersService extends AbstractController
{
    public function filtersList(
        Request $request,
        RecipeRepository $recipeRepository
    ) {

        $form = $this->createForm(FiltersSearchRecipeType::class, null);
        $form->handleRequest($request);

        $return = [];
        if ($form->isSubmitted() && $form->isValid()) {
            $recipeMenu = $form->get('recipeMenu')->getData();
            $recipeCategory = $form->get('recipeCategory')->getData();
            $tags = $form->get('tags')->getData();

            $recipies = $recipeRepository->findRecipiesByFilters($recipeMenu, $recipeCategory);

            $return['form'] = $form->createView();
            $return['recipies'] = $recipies;

            dd($return);
            return $return;
        }
    }
}