<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Entity\RecipeIngredient;
use App\Entity\RecipeStep;
use App\Entity\Tag;
use App\Entity\UserFavRecipe;
use App\Form\Type\RecipeType;
use App\Repository\IngredientRepository;
use App\Repository\RecipeCategoryRepository;
use App\Repository\RecipeMenuRepository;
use App\Repository\RecipeRepository;
use App\Repository\ShoppingListRepository;
use App\Repository\TagRepository;
use App\Repository\UserFavRecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/recipe")
 */
class RecipeController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function index()
    {
        return $this->render('recipe/index.html.twig', [
            'controller_name' => 'RecipeController',
        ]);
    }

    /**
     * @Route("/list",
     *  name="recipe_list")
     */
    public function allRecipies(
        RecipeCategoryRepository $categoriesRepo,
        RecipeRepository $recipeRepository,
        RecipeMenuRepository $menusRepo,
        TagRepository $tagRepository,
        UserFavRecipeRepository $userFavRepo,
        Request $request
    ) {
        if (!null == $this->getUser()) {
            $currentUser = $this->getUser();
        } else {
            $currentUser = null;
        }

        $recipeMenu = '';
        $recipeCategory = '';
        if ($request->isMethod('POST')) {
            if (!empty($_POST['filter-recipies-menu'])) {
                $recipeMenuStr = $_POST['filter-recipies-menu'];
                $recipeMenuRepo = $menusRepo->findOneBy([
                    'name' => $recipeMenuStr
                ]);
                $recipeMenu = $recipeMenuRepo->getId();
            }
            if (!empty($_POST['filter-recipies-category'])) {
                $recipeCategoryStr = $_POST['filter-recipies-category'];
                $recipeCategRepo = $categoriesRepo->findOneBy([
                    'name' => $recipeCategoryStr
                ]);
                $recipeCategory = $recipeCategRepo->getId();
            }
            $recipies = $recipeRepository->findRecipiesByFilters($recipeMenu, $recipeCategory);
        } else {
            $recipies   = $recipeRepository->findAll();
        }

        $categories = $categoriesRepo->findAll();
        $menus      = $menusRepo->findAll();
        $tags       = $tagRepository->findAll();

        if (null !== $currentUser) {
            $shopLists  = $currentUser->getShoppingLists();
        } else {
            $shopLists = null;
        }

        if (null !== $currentUser) {
            $favs = $userFavRepo->findExistingFavByUser($currentUser->getId());
        } else {
            $favs = null;
        }

        return $this->render('recipe/all.recipies.html.twig', [
            'recipies'      => $recipies,
            'favs'          => $favs,
            'categories'    => $categories,
            'menus'         => $menus,
            'tags'          => $tags,
            'shopLists'     => $shopLists
        ]);
    }

    /**
     * @Route("/user/list",
     *  name="user_recipe_list")
     * 
     * @Route("/favs/list", name="user_favs_recipe_list")
     *
     */
    public function userRecipeList(
        RecipeCategoryRepository $categoriesRepo,
        RecipeRepository $recipeRepository,
        RecipeMenuRepository $menusRepo,
        TagRepository $tagRepository,
        ShoppingListRepository $shopRepo,
        UserFavRecipeRepository $userFavRepo,
        Request $request
    ) {
        if (!null == $this->getUser()) {
            $currentUser = $this->getUser();
        }

        $pathInfo   = $request->getPathInfo();
        $categories = $categoriesRepo->findAll();
        $menus      = $menusRepo->findAll();
        $tags       = $tagRepository->findAll();
        $shopLists  = $shopRepo->findAll();
        $favs       = $userFavRepo->findExistingFavByUser($currentUser->getId());

        if ("/recipe/user/list" == $pathInfo) {
            $recipies   = $currentUser->getRecipies();
        } elseif ("/recipe/favs/list" == $pathInfo) {
            $recipies = [];
            foreach ($favs as $i => $fav) {
                $recipies[$i] = $recipeRepository->findBy([
                    'id' => $fav->getRecipeId()
                ]);
            }

            return $this->render('recipe/favs.html.twig', [
                'recipies'      => $recipies,
                'categories'    => $categories,
                'menus'         => $menus,
                'tags'          => $tags,
                'shopLists'     => $shopLists
            ]);
        }

        return $this->render('recipe/list.html.twig', [
            'recipies'      => $recipies,
            'favs'          => $favs,
            'categories'    => $categories,
            'menus'         => $menus,
            'tags'          => $tags,
            'shopLists'     => $shopLists
        ]);
    }


    /**
     * @Route("/view/{id}", name="recipe_view", methods={"GET"})
     */
    public function recipeView(Recipe $recipe, ShoppingListRepository $shopRepo): Response
    {
        $timePrepa    = $recipe->getTimePrepa();
        $hoursPrepa   = $timePrepa->format('H');
        $minutesPrepa = $timePrepa->format('i');
        $timeCook     = $recipe->getTimeCook();
        $hoursCook    = $timeCook->format('H');
        $minutesCook  = $timeCook->format('i');
        $shopLists    = $shopRepo->findAll();


        return $this->render('recipe/view.html.twig', [
            'recipe'        => $recipe,
            'hoursPrepa'    => $hoursPrepa,
            'minutesPrepa'  => $minutesPrepa,
            'hoursCook'     => $hoursCook,
            'minutesCook'   => $minutesCook,
            'shopLists'     => $shopLists
        ]);
    }

    /**
     * @Route("/add", name="recipe_add", methods={"GET","POST"})
     */
    public function recipeAdd(Request $request, IngredientRepository $ingredientRepository): Response
    {
        if (null !== $this->getUser()) {
            $user = $this->getUser();
        }

        $recipe = new Recipe();
        $recipe->setUser($user);

        $newStep = new RecipeStep();
        $newStep->setRecipe($recipe);
        $recipe->getRecipeSteps()->add($newStep);

        $recipeIngredients = new RecipeIngredient();
        $recipeIngredients->setRecipe($recipe);
        $recipe->getRecipeIngredients()->add($recipeIngredients);

        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dataFormIngredients = $form->get('recipeIngredients')->getData();

            // If Ingredient no existing in db we create a new one
            if (null !== $dataFormIngredients) {

                foreach ($dataFormIngredients as $newIngredient) {
                    $isIngredientExist = $ingredientRepository->findOneBy([
                        'name' => $newIngredient->getName()
                    ]);

                    if (!$isIngredientExist) {
                        $createNewIngredient = new Ingredient();
                        $createNewIngredient->setName($newIngredient->getName());
                        $this->em->persist($createNewIngredient);
                    }
                }
            }

            // Tags
            $tags = $form->get('tags')->getData();
            foreach ($tags as $tag) {
                $recipe->addTag($tag);
            }

            // Images
            $image = $form->get('recipePhoto')->getData();
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $originalFilename;
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();

                try {
                    $image->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    echo "L'image n'a pas été chargée";
                }
                $recipe->setRecipePhoto($newFilename);
            }

            $this->em->persist($newStep);
            $this->em->persist($recipeIngredients);
            $this->em->persist($recipe);
            $this->em->flush();

            $this->addFlash('success', 'La nouvelle recette ' . $recipe->getName() . 'a bien été ajoutée !');

            return $this->redirectToRoute('recipe_view', [
                'id' => $recipe->getId(),
            ]);
        }

        return $this->render('recipe/create.html.twig', [
            'recipe' => $recipe,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     *
     * @Route("/add/tags", name="add_tags")
     *
     * @return Response
     */
    public function addTags(Request $request): JsonResponse
    {
        $tagRepository = $this->entityManager->getRepository(Tag::class);

        if (isset($_GET['t'])) {
            $tags = $tagRepository->nameLike($_GET['t']);
        } else {
            $tags = $tagRepository->findAll();
        }

        $tagArray = [];
        /** @var Tag $tag */
        foreach ($tags as $tag) {
            $tagArray[] = ['id' => $tag->getId(), 'text' => $tag->getName()];
        }

        return new JsonResponse($tagArray);
    }

    /**
     * @Route("/update/{id}", name="recipe_update", methods={"GET","POST"})
     */
    public function recipeUpdate(Request $request, Recipe $recipe)
    {
        //$this->denyAccessUnlessGranted('edit', $recipe);

        if (null === $recipe) {
            throw $this->createNotFoundException('Recette non existente... ' . $recipe->getId);
        }

        $originalIngredients = new ArrayCollection();
        $originalSteps = new ArrayCollection();

        // Create an ArrayCollection of the current Ingredient objects in the database
        foreach ($recipe->getRecipeSteps() as $step) {
            $originalSteps->add($step);
        }

        if (!null === $recipe->getRecipeIngredients()) {
            foreach ($recipe->getRecipeIngredients() as $ingredient) {
                $originalIngredients->add($ingredient);
            }
        }

        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $amount = $form->getData();
            foreach ($amount->getRecipeIngredients() as $ingredient) {
                $ingredient->convertAmountsAndUnits();
            }
            foreach ($originalSteps as $step) {
                if (false === $recipe->getRecipeSteps()->contains($step)) {
                    $step->setRecipe(null);
                    $this->em->persist($step);
                }
            }

            foreach ($originalIngredients as $ingredient) {
                if (false === $recipe->getRecipeIngredients()->contains($ingredient)) {
                    $ingredient->setRecipe(null);
                    $this->em->persist($ingredient);
                }
            }

            // Images
            $image = $form->get('recipePhoto')->getData();
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $originalFilename;
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();

                try {
                    $image->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    echo "L'image n'a pas été chargée";
                }
                $recipe->setRecipePhoto($newFilename);
            }

            $this->em->persist($step);
            $this->em->flush();

            $this->addFlash('success', 'La recette a bien été mise à jour !');

            return $this->redirectToRoute('recipe_view', [
                'id' => $recipe->getId()
            ]);
        }

        return $this->render(
            'recipe/create.html.twig',
            [
                'recipe' => $recipe,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/delete", name="recipe_delete", methods={"GET","POST"})
     */
    public function recipeDelete(Recipe $recipe)
    {
        $this->denyAccessUnlessGranted('edit', $recipe);

        if (!null == $recipe->getRecipePhoto()) {
            $fileSystem = new Filesystem();
            $dir = $this->getParameter('images_directory');
            $photoName = $recipe->getRecipePhoto();
            $fileSystem->remove($dir . '/' . $photoName);
        }

        $this->em->remove($recipe);
        $this->em->flush();

        $this->addFlash('success', 'La recette a bien été supprimée');

        return $this->redirectToRoute('user_recipe_list');
    }

    /**
     * @Route("/filters", name="recipe_filters", methods={"GET","POST"})
     */
    public function recipeFilters(RecipeCategoryRepository $recipeCategoryRepository)
    {
        $categories = $recipeCategoryRepository->findAll();

        return $this->render(
            'modals/_filters.html.twig',
            [
                'categories' => $categories,
            ]
        );
    }


    /**
     * @Route("/set/favs/{id}", name="set_to_favs", methods={"GET", "POST"}, options={"expose"=true})
     */
    public function setFavsAjax(
        Request $request,
        RecipeRepository $recipeRepository,
        UserFavRecipeRepository $userFavRepo
    ) {
        if (null !== $this->getUser()) {
            $user = $this->getUser();
        }

        if ($request->isMethod('POST')) {
            $recipeId = $request->getContent();
            $recipe = $recipeRepository->findOneBy([
                'id' => $recipeId
            ]);
        }

        if (null !== $recipe) {

            $isFavExist = $userFavRepo->findFavByUserAndRecipe($recipe->getId(), $user->getId());

            if ($isFavExist) {
                $this->em->remove($isFavExist[0]);
            } else {
                $newFav = new UserFavRecipe;
                $newFav->setRecipeId($recipeId);
                $newFav->setUserId($user->getId());
                $this->em->persist($newFav);
            }
            $this->em->flush();
        }

        return $this->json([
            'ok'
        ]);
    }

    /**
     * @Route("/remove/favs/{id}", name="remove_favs", methods={"GET", "POST"}, options={"expose"=true})
     */
    public function removeFav(
        Request $request,
        RecipeRepository $recipeRepository,
        UserFavRecipeRepository $userFavRepo
    ) {
        if (null !== $this->getUser()) {
            $user = $this->getUser();
        }

        if ($request->isMethod('POST')) {
            $recipeId = $request->getContent();
            $recipe = $recipeRepository->findOneBy([
                'id' => $recipeId
            ]);
        }

        if (null !== $recipe) {
            $isFavExist = $userFavRepo->findFavByUserAndRecipe($recipeId, $user->getId());

            if ($isFavExist) {
                $this->em->remove($isFavExist[0]);
                $this->em->flush();
            }
        }

        return $this->json([
            'ok'
        ]);
    }
}
