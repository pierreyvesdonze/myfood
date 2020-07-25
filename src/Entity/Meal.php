<?php

namespace App\Entity;

use App\Repository\MealRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MealRepository::class)
 */
class Meal
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=MealPlan::class, inversedBy="meals")
     * @ORM\JoinColumn(nullable=false)
     */
    private $mealPlans;

    /**
     * @ORM\ManyToOne(targetEntity=Recipe::class, inversedBy="meals")
     * @ORM\JoinColumn(nullable=false)
     */
    private $recipies;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    public function __toString()
    {
        return $this->name;
    }

    public function __construct()
    {
        $this->date = new \DateTime();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMealPlans(): ?MealPlan
    {
        return $this->mealPlans;
    }

    public function setMealPlans(?MealPlan $mealPlans): self
    {
        $this->mealPlans = $mealPlans;

        return $this;
    }

    public function getRecipies(): ?Recipe
    {
        return $this->recipies;
    }

    public function setRecipies(?Recipe $recipies): self
    {
        $this->recipies = $recipies;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
