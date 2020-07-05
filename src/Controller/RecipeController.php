<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Entity\RecipeStep;
use App\Form\Type\RecipeType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

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
     * @Route("/list/{id}", name="recipe_list")
     */
    public function recipeList(Recipe $recipe)
    {
        return $this->render('recipies/view.html.twig', [
            'recipe' => $recipe
        ]);
    }

    /**
     * @Route("/add", name="recipe_add", methods={"GET","POST"})
     */
    public function recipeAdd(Request $request): Response
    {
        $recipe = new Recipe();
        $user = $this->getUser();
        $recipe->setUser($user);

        $newStep = new RecipeStep();
        $newStep->setRecipe($recipe);
        $recipe->getRecipeSteps()->add($newStep);

        $ingredient = new Ingredient();
        $ingredient->setRecipe($recipe);
        $recipe->getIngredients()->add($ingredient);

        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($recipe);
            $entityManager->persist($newStep);
            $entityManager->persist($ingredient);

            $entityManager->flush();

            $this->addFlash("success", "Merci d'avoir ajouté une nouvelle recette ! ");
            return $this->redirectToRoute('homepage');
        }

        return $this->render('recipe/create.html.twig', [
            'recipe' => $recipe,
            'form'   => $form->createView(),
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

        if (null === $recipe ){
            throw $this->createNotFoundException('No task found for id '.$recipe->getId);
        }

        $originalIngredients = new ArrayCollection();
        $originalSteps = new ArrayCollection();

        // Create an ArrayCollection of the current Ingredient objects in the database
        foreach ($recipe->getRecipeSteps() as $step) {
            $originalSteps->add($step);
        }

        foreach ($recipe->getIngredients() as $ingredient) {
            $originalIngredients->add($ingredient);
        }
    
        $form = $this->createForm(ShoppingListType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($originalSteps as $step) {
                if (false === $recipe->getRecipeSteps()->contains($step)) {
               
                    $step->setRecipe(null);
                    $manager->persist($step);

                }
            }

            foreach ($originalIngredients as $ingredient) {
                if (false === $recipe->getIngredients()->contains($ingredient)) {
               
                    $ingredient->setRecipe(null);
                    $manager->persist($ingredient);
                    //$manager->remove($ingredient);
                }
            }
    
            $manager->persist($step);
            $manager->persist($ingredient);
            $manager->flush();

            $this->addFlash("success", "Ta recette a bien été mise à jour");
            return $this->redirectToRoute('hompage');
        }

        return $this->render(
            "recipies/update.html.twig",
            [
                "recipe" => $recipe,
                "form"   => $form->createView()
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
        $manager->remove($recipe);
        $manager->flush();

        $this->addFlash("success", "La recette a bien été supprimée");

        return $this->redirectToRoute('hompeage');
    }
}