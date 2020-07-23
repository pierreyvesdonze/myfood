<?php

namespace App\Controller;

use App\Form\Type\SearchRecipeType;
use App\Repository\RecipeIngredientRepository;
use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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

            // Sorry for this :/
            $arrayIngredients = explode(', ', $dataIngredients);
            $text = str_replace("'", '', $arrayIngredients);
            $text2 = str_replace("[", '', $text);
            $text3 = str_replace("]", '', $text2);
            $recipiesArray = [];

            // Get matching RecipeIngredient
            foreach ($text3 as $data) {
                /**
                 * @return RecipeIngredient()
                 */
                $recipeIngredients = $recipeIngredientRepository->findBy([
                    'name' => $data
                ]);

                foreach ($recipeIngredients as $recipeIng) {
                    $name = $recipeIng->getName();

                    $recipies = $recipeRepository->findRecipeByName($name);
                }
            }
            dump($recipiesArray);

            // Built array with names & ids of recipies
            foreach ($recipies as $key => $recipe) {
                $recipiesArray[] = [
                    'id' => $recipe->getId(),
                    'name' => $recipe->getName()
                ];
            }
        }
        return $this->json([
            'recipies' => $recipiesArray
        ]);

        return new Response(
            'Something wrong...',
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );
    }

    /**
     * @Route("/by-ingredient", name="search_list_by_ingredients", methods={"GET", "POST"})
     */
    public function searchByIngredients(Request $request)
    {


        $form = $this->createForm(SearchRecipeType::class, null);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $dataFormIngredients = $form->get('recipeIngredients')->getData();
        }
        return $this->render('shopList/create.by.ingredients.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
