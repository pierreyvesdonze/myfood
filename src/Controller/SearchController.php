<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Entity\RecipeStep;
use App\Entity\Ingredient;
use App\Form\Type\RecipeType;
use App\Entity\RecipeIngredient;
use App\Repository\RecipeRepository;
use App\Repository\IngredientRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/search")
 */
class SearchController extends AbstractController
{
    /**
     * @Route("/recipe/api", name="searchApi", options={"expose"=true})
     */
    public function findRecipe(RecipeRepository $recipeRepository, Request $request)
    {

        if ($request->isMethod('POST')) {

            $data = json_decode($request->getContent());
            $recipies = $recipeRepository->findRecipeByName($data);
            $recipiesArray = [];
            foreach ($recipies as $recipe) {
                $recipiesArray[] = array($recipe->getName());
            }

            return new JsonResponse($recipiesArray);
//            return $this->json([
//                'recipies' => $recipies
//            ]);
        }

//        if ($request->isXMLHttpRequest()) {
//            $data = json_decode($request->getContent());
//            $recipies = $recipeRepository->findRecipeByName($data);
//            return $this->json([
//                'recipies' => $recipies
//            ]);
//        }
        return new Response('This is not ajax!', 400);
//        return $this->redirectToRoute('homepage');
    }
}
