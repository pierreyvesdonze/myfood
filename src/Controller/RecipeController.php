<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Entity\RecipeIngredient;
use App\Entity\RecipeStep;
use App\Form\Type\RecipeType;
use App\Repository\IngredientRepository;
use App\Repository\RecipeCategoryRepository;
use App\Repository\RecipeMenuRepository;
use App\Repository\RecipeRepository;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/recipe")
 */
class RecipeController extends AbstractController
{
    public function index()
    {
        return $this->render('recipe/index.html.twig', [
            'controller_name' => 'RecipeController',
        ]);
    }

    /**
     * @Route("/list", name="recipe_list")
     */
    public function recipeList(
        RecipeCategoryRepository $categoriesRepo,
        RecipeMenuRepository $menusRepo,
        TagRepository $tagRepository
    )
    {
        $user = $this->getUser();
        /*    $recipies = $recipeRepository->findAll(); */
        $recipies = $user->getRecipies();
        $categories = $categoriesRepo->findAll();
        $menus = $menusRepo->findAll();
        $tags = $tagRepository->findAll();

        return $this->render('recipe/list.html.twig', [
            'recipies' => $recipies,
            'categories' => $categories,
            'menus' => $menus,
            'tags' => $tags,
        ]);
    }


    /**
     * @Route("/view/{id}", name="recipe_view", methods={"GET"})
     */
    public function recipeView(Recipe $recipe): Response
    {
        $timePrepa = $recipe->getTimePrepa();
        $hoursPrepa = $timePrepa->format('H');
        $minutesPrepa = $timePrepa->format('i');
        $timeCook = $recipe->getTimeCook();
        $hoursCook = $timeCook->format('H');
        $minutesCook = $timeCook->format('i');

        return $this->render('recipe/view.html.twig', [
            'recipe' => $recipe,
            'hoursPrepa' => $hoursPrepa,
            'minutesPrepa' => $minutesPrepa,
            'hoursCook' => $hoursCook,
            'minutesCook' => $minutesCook,
        ]);
    }

    /**
     * @Route("/add", name="recipe_add", methods={"GET","POST"})
     */
    public function recipeAdd(Request $request, IngredientRepository $ingredientRepository): Response
    {
        $recipe = new Recipe();
        $user = $this->getUser();
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
            $entityManager = $this->getDoctrine()->getManager();
            $dataFormIngredients = $form->get('recipeIngredients')->getData();

            // If Ingredient no existing in db we create new
            foreach ($dataFormIngredients as $newIngredient) {
                $isIngredientExist = $ingredientRepository->findOneBy([
                    'name' => $newIngredient->getName()
                ]);

                if (!$isIngredientExist) {
                    $createNewIngredient = new Ingredient();
                    $createNewIngredient->setName($newIngredient->getName());
                    $entityManager->persist($createNewIngredient);
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

            $entityManager->persist($recipe);
            $entityManager->persist($newStep);
            $entityManager->persist($recipeIngredients);

            $entityManager->flush();

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
     * @Route("/{id}/update", name="recipe_update", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function recipeUpdate(Request $request, Recipe $recipe)
    {
        $this->denyAccessUnlessGranted('edit', $recipe);

        $manager = $this->getDoctrine()->getManager();

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
                    $manager->persist($step);
                }
            }

            foreach ($originalIngredients as $ingredient) {
                if (false === $recipe->getRecipeIngredients()->contains($ingredient)) {
                    $ingredient->setRecipe(null);
                    $manager->persist($ingredient);
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

            $manager->persist($step);
            $manager->flush();

            $this->addFlash('success', 'La recette a bien été mise à jour !');

            /*     return $this->redirectToRoute('recipe_view', [
                    'id' => $recipe->getId()
                ]); */
        }

        return $this->render(
            'recipe/edit.html.twig',
            [
                'recipe' => $recipe,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/delete", name="recipe_delete", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function recipeDelete(Recipe $recipe)
    {
        $this->denyAccessUnlessGranted('edit', $recipe);

        $manager = $this->getDoctrine()->getManager();

        if (!null == $recipe->getRecipePhoto()) {
            $fileSystem = new Filesystem();
            $dir = $this->getParameter('images_directory');
            $photoName = $recipe->getRecipePhoto();
            $fileSystem->remove($dir . '/' . $photoName);
        }
        $manager->remove($recipe);
        $manager->flush();

        $this->addFlash('success', 'La recette a bien été supprimée');

        return $this->redirectToRoute('recipe_list');
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
}
