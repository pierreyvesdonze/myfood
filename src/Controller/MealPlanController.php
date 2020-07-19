<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/*
 * @Roule("/meal")
 */
class MealPlanController extends AbstractController
{
    public function index()
    {
        return $this->render('meal_plan/index.html.twig', [
            'controller_name' => 'MealPlanController',
        ]);
    }


   
}
