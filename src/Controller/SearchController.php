<?php

namespace App\Controller;

use App\Repository\RecipeIngredientRepository;
use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
                $recipiesArray[] = $recipe->getName();
            }

            return new JsonResponse($recipiesArray);
        }

        return new Response('This is not ajax!', 400);
    }

    /**
     * @Route("/by-ingredient-ajax", name="shopping_list_by_ingredients_ajax", options={"expose"=true}, methods={"GET","POST"})
     *
     * @param Request $request
     * @param RecipeRepository $recipeRepository
     * @return JsonResponse|Response
     *
     */
    public function createShopplistByIngredientsAjax(Request $request, RecipeIngredientRepository $recipeIngredientRepository, RecipeRepository $recipeRepository)
    {
        if ($request->isMethod('POST')) {

            $dataIngredients = json_decode($request->getContent());
            $dataIngredients = $request->getContent();

            // Bidouille super merdique mais je n'ai trouvé que ça pour l'instant
            $arrayIngredients = explode(', ', $dataIngredients);
            $text = str_replace("'", '', $arrayIngredients);
            $text2 = str_replace("[", '', $text);
            $text3 = str_replace("]", '', $text2);
            $recipiesArray = [];
            $finalArray = [];

            // Get matching RecipeIngredient
            foreach ($text3 as $data) {

                /**
                 * @return RecipeIngredient()
                 */
                $recipeIngredient = $recipeIngredientRepository->findBy([
                    'name' => $data
                ]);

                foreach ($recipeIngredient as $recipeIng) {
                    $name = $recipeIng->getName();
                    $recipeIngStr = strval($name);
                    $recipeRepository->findRecipeByName($recipeIngStr);

                    /**
                     * @return Recipe()
                     */
                    $recipies = $recipeRepository->findBy([
                        'id' => $recipeIng->getRecipe()
                    ]);

                    $recipiesArray = $recipies;
                }

                foreach ($recipiesArray as $name) {
                    $finalArray[0] = $name->getName();
                    $finalArray[1] = $name->getId();
                }
            }

            dump($finalArray);


            $response = new Response();
            $response->setContent(json_encode([
                'recipies' => $finalArray,
            ]));

            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
        return new Response(
            'Content',
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );
    }
}
