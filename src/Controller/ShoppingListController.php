<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Entity\ShoppingList;
use App\Form\Type\ShoppingListType;
use App\Repository\ArticleRepository;
use App\Repository\IngredientRepository;
use App\Repository\ShoppingListRepository;
use App\Repository\UnitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        return $this->render('shopping_list/show.html.twig', [
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
            'shoppingList' => $shopList,
        ]);
    }

    /**
     * **************************
     * OPTIONAL
     * **************************
     */

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
     * @Route("/create/{id}", name="shopping_list_create", methods={"GET", "POST"}, options={"expose"=true})
     */
    public function shoppingListCreate(Request $request, Recipe $recipe): Response
    {
        $em = $this->getDoctrine()->getManager();
        if ($request->isMethod('POST')) {
            /** @var ShoppingList */
            $shoppingList = new ShoppingList();
            $ingredients = $recipe->getRecipeIngredients();
            $description = $recipe->getName();
            $shoppingList->setDescription($description);

            foreach ($ingredients as $ingredient) {
                $article = new Article();
                $article->setName($ingredient->getName());
                $article->setAmount($ingredient->amount);
                $article->setUnit($ingredient->getUnit());
                $article->setShoppingList($shoppingList);
                $em->persist($article);
            }
            $em->flush();

            $this->addFlash('success', 'La liste de course a bien été créé !');
            return $this->json([
                'ok'
            ]);
        }

        return new Response(
            'Something went wrong...',
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );
    }

    /**
     * @Route("/add/{id}", name="shopping_list_add", methods={"GET", "POST"}, options={"expose"=true})
     */
    public function shoppingListAdd(Request $request, Recipe $recipe, ShoppingListRepository $shoppingListRepository)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        if ($request->isMethod('POST')) {
            $shopRequest = $request->getContent();
            $strparts    = explode("shopListToAdd", $shopRequest);
            $shopArray   = preg_replace('/[^0-9]/', '', $strparts);
            $shopToAdd   = $shopArray[1];

            $shoppingList = $shoppingListRepository->findOneBy([
                'id' => $shopToAdd
            ]);

            // Make an array with old list's ingredients
            $oldListArray = [];
            $oldList = $shoppingList->getArticles();
            for ($i = 0; $i < count($oldList); ++$i) {
                $oldListArray[$i]['name']   = $oldList[$i]->getName();
                $oldListArray[$i]['amount'] = intval($oldList[$i]->getAmount());
                $oldListArray[$i]['unit']   = $oldList[$i]->getUnit();
            }

            // Make an array with new list
            $newListArray = [];
            $truc = $recipe->getRecipeIngredients();
            for ($j = 0; $j < count($truc); ++$j) {
                $newListArray[$j]['name']   = $truc[$j]->getName();
                $newListArray[$j]['amount'] = $truc[$j]->getAmount();
                $newListArray[$j]['unit']   = $truc[$j]->getUnit();
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

            $this->addFlash('success', "La liste de courses a bien été ajoutée !");

            return $this->json([
                'ok'
            ]);
        }

        return new Response(
            'Something went wrong...',
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );
    }

    /**
     * @Route("/add/articles/{id}", name="shopping_list_add_articles", methods={"GET", "POST"}, options={"expose"=true})
     */
    public function shoppingListAddArticle(
        Request $request,
        IngredientRepository $ingredientRepository,
        ShoppingListRepository $shoppingListRepository,
        UnitRepository $unitRepository
    ) {
        if ($request->isMethod('POST')) {
            $requestIngredients =  json_decode($request->getContent());
            $em = $this->getDoctrine()->getManager();

            foreach ($requestIngredients as $key => $article) {
                $shopListId = $article->id;
            }

            $shopList = $shoppingListRepository->findOneBy([
                'id' => $shopListId
            ]);

            foreach ($requestIngredients as $key => $article) {

                /**
                 * @return Ingredient()
                 */
                $dataIngredient = $ingredientRepository->findBy([
                    'name' => $article->name
                ]);

                if (null == $dataIngredient) {
                    $newIngredient = new Ingredient();
                    $newIngredient->setName($article->name);
                    $em->persist($newIngredient);
                }

                $newAmount = (int)$article->amount;
                $newArticle = new Article();
                $newArticle->setName($article->name);
                $newArticle->setAmount($newAmount);

                $newArticleUnit = $unitRepository->findBy([
                    'name' => $article->unit
                ]);

                foreach ($newArticleUnit as $newUnit) {
                    $newArticle->setUnit($newUnit);
                }
                foreach ($newArticle as $art) {
                    $em->persist($art[$key]);
                }
                $shopList->addArticle($newArticle);
            }

            $em->persist($shopList);
            $em->flush();

            return $this->json([
                'ok'
            ]);
        }

        return new Response(
            'Something went wrong...',
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );
    }

    /**
     * @Route("/{id}/delete", name="shopping_list_delete", methods={"GET","POST"})
     */
    public function shoppingListDelete(ShoppingList $shoppingList): Response
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($shoppingList);
        $manager->flush();

        $this->addFlash('success', 'La liste de courses a bien été supprimée');

        return $this->redirectToRoute('shopping_list_list');
    }

    /**
     * @Route("/article/{id}/delete", name="article_delete", methods={"GET","POST"}, options={"expose"=true})
     */
    public function articleDelete(Request $request, ArticleRepository $articleRepository): Response
    {
        if ($request->isMethod('POST')) {
            $articleRequest =  $request->getContent();
            $em = $this->getDoctrine()->getManager();
            $articleRequest = preg_replace('/[^0-9]/', '', $articleRequest);
            $article = $articleRepository->findOneBy([
                'id' => $articleRequest
            ]);

            $em->remove($article);
            $em->flush();

            return $this->json([
                'ok'
            ]);
        }

        return new Response(
            'Something went wrong...',
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );
    }

    /**
     * @Route("/article/{id}/increase/amount", name="article_increase_amount", methods={"GET","POST"}, options={"expose"=true})
     */
    public function increaseAmountApi(Request $request, ArticleRepository $articleRepository)
    {
        if ($request->isMethod('POST')) {
            $em             = $this->getDoctrine()->getManager();
            $articleRequest =  $request->getContent();
            $strparts       = explode("amount", $articleRequest);
            $articleArray   = preg_replace('/[^0-9]/', '', $strparts);
            $articleId      = $articleArray[0];
            $articleAmount  = $articleArray[1];

            $article = $articleRepository->findOneBy([
                'id' => $articleId
            ]);

            $article->setAmount($articleAmount);
            $em->persist($article);
            $em->flush();

            return $this->json([
                'ok'
            ]);
        }

        return new Response(
            'Something went wrong...',
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );
    }
}
