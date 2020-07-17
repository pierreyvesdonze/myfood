<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
