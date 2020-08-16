<?php

namespace App\Controller;

use App\Entity\MealPlan;
use App\Form\Type\MealPlanType;
use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/*
 * @Roule("/meal")
 */

class MealPlanController extends AbstractController
{
    /**
     * @Route("/show/{id}", name="meal_plan_show")
     */
    public function show(MealPlan $mealPlan)
    {
        return $this->render('meal_plan/show.html.twig', [
            'mealplan' => $mealPlan,
        ]);
    }

    /**
     *@Route("/add", name="meal_plan_add", methods={"GET", "POST"})
     */
    public function addMealPLan(Request $request, RecipeRepository $recipeRepository)
    {

        $mealPlan = new MealPlan();
        $user = $this->getUser();
        $mealPlan->setUser($user);

        $form = $this->createForm(MealPlanType::class, $mealPlan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mealPlan);
            $entityManager->flush();

            // Recipies
/*             $recipies = $form->get('recipies')->getData();
            foreach ($recipies as $recipe) {
                $mealPlan->addRecipe($recipe['name']);
            } */

            $this->addFlash('success', 'Le planning de la semaine '.$mealPlan->getName() .'a bien été ajouté !');

            return $this->redirectToRoute('meal_plan_show', [
                'id' => $mealPlan->getId(),
            ]);

        }

        return $this->render('meal_plan/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
