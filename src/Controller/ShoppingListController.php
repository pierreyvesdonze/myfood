<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Recipe;
use App\Entity\ShoppingList;
use App\Form\Type\ShoppingListByIngredientsType;
use App\Form\Type\ShoppingListType;
use App\Repository\IngredientRepository;
use App\Repository\RecipeRepository;
use App\Repository\ShoppingListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/shopping_list")
 */
class ShoppingListController extends AbstractController
{
    public function index()
    {
        return $this->render('shopping_list/index.html.twig', [
            'controller_name' => 'ShoppingListController',
        ]);
    }

    /**
     * @Route("/list", name="shopping_list_list")
     */
    public function shoppingListList(ShoppingListRepository $shoppingListRepository)
    {
        $shopList = $shoppingListRepository->findAll();

        return $this->render('shopList/shopping_list_all.html.twig', [
            'shoppingLists' => $shopList,
        ]);
    }

    /**
     * @Route("/view/{id}", name="shopping_list_view", methods={"GET"})
     */
    public function shoppingListView(ShoppingList $shoppingList): Response
    {
        return $this->render('shopList/view.html.twig', [
            'shoppingList' => $shoppingList,
        ]);
    }

    /**
     * @Route("/create/{id}", name="shopping_list_create", methods={"GET", "POST"})
     */
    public function shoppingListCreate(Request $reques, Recipe $recipe)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var ShoppingList $shoppingList */
        $shoppingList = new ShoppingList();
        $ingredients = $recipe->getRecipeIngredients();
        $description = $recipe->getName();
        $shoppingList->setDescription($description);

        foreach ($ingredients as $ingredient) {
            $article = new Article();
            $article->setName($ingredient->getName());
            $article->setAmount($ingredient->amount);
            $article->setUnit($ingredient->unit);
            $article->setShoppingList($shoppingList);
            $em->persist($article);
        }

        $em->flush();

        $this->addFlash('success', 'La liste de course a bien été créé !');

        return $this->redirectToRoute('shopping_list_view', [
            'id' => $shoppingList->getId(),
        ]);
    }

    /**
     * @Route("/add/{id}", name="shopping_list_add", methods={"GET", "POST"})
     */
    public function shoppingListAdd(Request $request, Recipe $recipe, ShoppingListRepository $shoppingListRepository)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $form = $this->createForm(ShoppingListType::class, null);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $oldShopList = $form->get('shoppingList')->getData();
            $shoppingList = $shoppingListRepository->findOneBy([
                'id' => $oldShopList->getId(),
            ]);

            // Make an array with old list's ingredients
            $oldListArray = [];
            $oldList = $oldShopList->getArticles();
            for ($i = 0; $i < count($oldList); ++$i) {
                $oldListArray[$i]['name'] = $oldList[$i]->getName();
                $oldListArray[$i]['amount'] = intval($oldList[$i]->getAmount());
                $oldListArray[$i]['unit'] = $oldList[$i]->getUnit();
            }

            // Make an array with new list
            $newListArray = [];
            $truc = $recipe->getRecipeIngredients();
            for ($j = 0; $j < count($truc); ++$j) {
                $newListArray[$j]['name'] = $truc[$j]->getName();
                $newListArray[$j]['amount'] = $truc[$j]->getAmount();
                $newListArray[$j]['unit'] = $truc[$j]->getUnit();
            }

            // Merge lists and build final
            $mergedList = array_merge($oldListArray, $newListArray);
            $finalShoppingList = [];
            foreach ($mergedList as $unique) {
                $name = $unique['name'];

                // Merge duplicates and increase amounts
                if (isset($finalShoppingList[$name])) {
                    if ($finalShoppingList[$name]['name'] === $unique['name']) {
                        $finalShoppingList[$name]['amount'] += $unique['amount'];
                    }
                } else {
                    $finalShoppingList[$name] = $unique;
                }
            }

            // Clean the original list
            /*   foreach ($shoppingList->getArticles() as $cleanIngredient) {
                  $shoppingList->removeArticle($cleanIngredient);
              }  */

            // Save old informations
            $oldShopListId = $shoppingList->getId();
            $oldShopListName = $shoppingList->getDescription();

            // Remove old shopping list
            $em->remove($shoppingList);
            $em->flush();

            // Set new shopping list with old values + new articles
            $newShoppingList = new ShoppingList();
            $newShoppingList->setId($oldShopListId);
            $newShoppingList->setDescription($oldShopListName);
            $newShoppingList->setUser($user);
            foreach ($finalShoppingList as $final) {
                $newArticle = new Article();
                $newArticle->setName($final['name']);
                $newArticle->setAmount($final['amount']);
                $newArticle->setUnit($final['unit']);
                $newArticle->setShoppingList($newShoppingList);
                $em->persist($newArticle);
            }
            $em->persist($newShoppingList);
            $em->flush();

            return $this->redirectToRoute('shopping_list_view', [
                'id' => $newShoppingList->getId(),
            ]);
        }

        return $this->render('shopList/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="shopping_list_delete", methods={"GET","POST"})
     */
    public function shoppingListDelete(ShoppingList $shoppingList)
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($shoppingList);
        $manager->flush();

        $this->addFlash('success', 'La liste de courses a bien été supprimée');

        return $this->redirectToRoute('shopping_list_list');
    }

    /**
     * @Route("/by-ingredient", name="shopping_list_by_ingredients")
     */
    public function createShopplistByIngredients()
    {
        return $this->render('shopList/create.by.ingredients.html.twig');
    }


    /***
     *
     * @Route("/by-ingredient-ajax", name="shopping_list_by_ingredients_ajax", methods={"GET", "POST"})
     * @param Request $request
     * @param RecipeRepository $recipeRepository
     * @return JsonResponse|Response
     *
     */
    public function createShopplistByIngredientsAjax(Request $request, RecipeRepository $recipeRepository)
    {
        if ($request->isMethod('POST')) {
            $data = json_decode($request->getContent());
            $recipies = $recipeRepository->findRecipeByName($data);
            $recipiesArray = [];
            foreach ($recipies as $recipe) {
                $recipiesArray[] = [$recipe->getName()];
            }

            return new JsonResponse($recipiesArray);
        }

        return new Response('This is not ajax!', 400);
    }
}
