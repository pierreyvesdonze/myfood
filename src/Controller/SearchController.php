<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Entity\RecipeCategory;
use App\Entity\RecipeMenu;
use App\Entity\ShoppingList;
use App\Entity\Tag;
use App\Form\Type\FiltersSearchRecipeType;
use App\Form\Type\SearchRecipeType;
use App\Form\Type\SearchType;
use App\Repository\RecipeCategoryRepository;
use App\Repository\RecipeIngredientRepository;
use App\Repository\RecipeMenuRepository;
use App\Repository\RecipeRepository;
use App\Repository\ShoppingListRepository;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
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
        if ($request->isXmlHttpRequest()) {
            $data                = json_decode($request->getContent());
            $recipies            = $recipeRepository->findRecipeByName($data);
            $recipiesArray       = [];

            foreach ($recipies as $recipe) {
                $recipiesArray[] = $recipe;
            }
            return new JsonResponse($recipiesArray);
        }
        return $this->render('search/result.search.html.twig', []);
    }

    public function searchBarAction(Request $request, RecipeRepository $recipeRepository): Response
    {
        $search = [];
        $form = $this->createForm(SearchType::class,  $search, [
            'action' => $this->generateUrl('search_transform'),
        ]);

        $form->handleRequest($request);
        $errors =  $form->getErrors(true, false);

        if ($form->isSubmitted() && $form->isValid()) {
            $data     = $form->getData();
            $recipies = $recipeRepository->findRecipeByName($data);

            return $this->redirectToRoute('user_recipe_list', [
                'recipies' => $recipies
            ]);
        }
        return $this->render('search/searchbar.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/transformsearch", name="search_transform")
     */
    public function transformAction(
        Request $request
    ): Response {
        $form = $this->createForm(SearchType::class, null, [
            'action' => $this->generateUrl('search_transform'),
        ]);

        $recipeRepository         = $this->getDoctrine()->getRepository(Recipe::class);
        $recipeCategoryRepository = $this->getDoctrine()->getRepository(RecipeCategory::class);
        $recipeMenuRepository     = $this->getDoctrine()->getRepository(RecipeMenu::class);
        $tagRepository            = $this->getDoctrine()->getRepository(Tag::class);
        $shopListRepository       = $this->getDoctrine()->getRepository(ShoppingList::class);

        return $this->saveSearch(
            $request,
            $form,
            $recipeRepository,
            $recipeCategoryRepository,
            $recipeMenuRepository,
            $tagRepository,
            $shopListRepository
        );
    }

    /**
     * @param $form
     */
    protected function saveSearch(
        Request $request,
        FormInterface $form,
        RecipeRepository $recipeRepository,
        RecipeCategoryRepository $categoriesRepo,
        RecipeMenuRepository $menusRepo,
        TagRepository $tagRepository,
        ShoppingListRepository $shopRepo
    ) {
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $data       = $form->getData()['text'];
            $recipies   = $recipeRepository->findRecipeByName($data);
            $categories = $categoriesRepo->findAll();
            $menus      = $menusRepo->findAll();
            $tags       = $tagRepository->findAll();
            $shopLists  = $shopRepo->findAll();

            if (!$recipies) {
                $this->addFlash("error", "Aucune recette n'a été trouvée :/ ");
            }

            return $this->render('recipe/list.html.twig', [
                'request'    => $request,
                'recipies'   => $recipies,
                'categories' => $categories,
                'menus'      => $menus,
                'tags'       => $tags,
                'shopLists'  => $shopLists
            ]);
        }
        return new RedirectResponse($this->generateUrl('homepage'));
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
            $text             = str_replace("'", '', $arrayIngredients);
            $text2            = str_replace("[", '', $text);
            $text3            = str_replace("]", '', $text2);
            $recipiesArray    = [];

            // Get matching RecipeIngredient
            foreach ($text3 as $data) {

                /**
                 * @return RecipeIngredient()
                 */
                $recipeIngredients = $recipeIngredientRepository->findBy([
                    'name' => $data
                ]);

                foreach ($recipeIngredients as $recipeIng) {
                    $name     = $recipeIng->getName();
                    $recipies = $recipeRepository->findRecipeByName($name);
                }
            }

            // Build array with names & ids of recipies
            foreach ($recipies as $key => $recipe) {
                $recipiesArray[] = [
                    'id'   => $recipe->getId(),
                    'name' => $recipe->getName()
                ];
            }
        }
        return $this->json([
            'recipies' => $recipiesArray
        ]);
        return new Response(
            'Something went wrong...',
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );
    }

    /**
     * @Route("/by-ingredient", name="search_list_by_ingredients", methods={"GET", "POST"})
     */
    public function searchByIngredients(
        Request $request,
        RecipeIngredientRepository $recipeIngredientRepository
    ) {
        $form = $this->createForm(SearchRecipeType::class, null);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dataFormIngredients = $form->get('ingredient')->getData();

            foreach ($dataFormIngredients as $key => $dataFormIngredient) {

                /**
                 * @var RecipeIngredient()
                 */
                $recipeIngredients[] = $recipeIngredientRepository->findBy([
                    'name' => $dataFormIngredient->getName()
                ]);
            }

            $recipiesArray = [];

            foreach ($recipeIngredients as $key => $value) {
                foreach ($value as $recipe) {
                    $recipiesArray[] = $recipe->getRecipe();
                }
            }

            if (!null == $recipiesArray) {
                return $this->render('recipe/list.html.twig', [
                    'recipies' => $recipiesArray,
                ]);
            } else {
                $this->addFlash("error", "Désolé, nous n'avons pas trouvé de recette correspondante");
            }
        }
        return $this->render('shopList/create.by.ingredients.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/result/filters", name="advanced_filters_list", methods={"GET","POST"})
     */
    public function filtersList(
        Request $request,
        RecipeRepository $recipeRepository
    ) {

        $form = $this->createForm(FiltersSearchRecipeType::class, null);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recipeMenu = $form->get('recipeMenu')->getData();
            $recipeCategory = $form->get('recipeCategory')->getData();
            $tags = $form->get('tags')->getData();

            $recipies = $recipeRepository->findRecipiesByFilters($recipeMenu, $recipeCategory);
            

            return $this->render('search/result.search.html.twig', [
                'recipies' => $recipies
            ]);
        }

        return $this->render('search/filters.search.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
