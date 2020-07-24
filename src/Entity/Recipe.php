<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecipeRepository")
 */
class Recipe
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank()
     * @Groups("group1")
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="recipes", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=RecipeStep::class, mappedBy="recipe", cascade={"persist", "remove"})
     */
    private $recipeSteps;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $timeCook;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $timePrepa;

    /**
     * @ORM\Column(type="integer")
     */
    private $person;

    /**
     * @ORM\OneToMany(targetEntity=RecipeIngredient::class, mappedBy="recipe", cascade={"persist", "remove"}, fetch="EAGER")
     * @ORM\JoinColumn(nullable=true)
     */
    private $recipeIngredients;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $recipePhoto;

    /**
     * @ORM\ManyToOne(targetEntity=RecipeCategory::class, inversedBy="recipe")
     */
    private $recipeCategory;

    /**
     * @ORM\ManyToOne(targetEntity=RecipeMenu::class, inversedBy="recipe")
     * @ORM\JoinColumn(nullable=false)
     */
    private $recipeMenu;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="recipe", cascade={"persist"})
     */
    private $tags;

    /**
     * @ORM\ManyToMany(targetEntity=MealPlan::class, mappedBy="recipes")
     */
    private $mealPlans;

    public function __construct()
    {
        $this->recipeSteps = new ArrayCollection();
        $this->recipeIngredients = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->mealPlans = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|RecipeStep[]
     */
    public function getRecipeSteps(): Collection
    {
        return $this->recipeSteps;
    }

    public function addRecipeStep(RecipeStep $recipeStep): self
    {
        if (!$this->recipeSteps->contains($recipeStep)) {
            $this->recipeSteps[] = $recipeStep;
            $recipeStep->setRecipe($this);
        }

        return $this;
    }

    public function removeRecipeStep(RecipeStep $recipeStep): self
    {
        if ($this->recipeSteps->contains($recipeStep)) {
            $this->recipeSteps->removeElement($recipeStep);
            // set the owning side to null (unless already changed)
            if ($recipeStep->getRecipe() === $this) {
                $recipeStep->setRecipe(null);
            }
        }

        return $this;
    }

    public function getTimeCook(): ?\DateTimeInterface
    {
        return $this->timeCook;
    }

    public function setTimeCook(?\DateTimeInterface $timeCook): self
    {
        $this->timeCook = $timeCook;

        return $this;
    }

    public function getTimePrepa(): ?\DateTimeInterface
    {
        return $this->timePrepa;
    }

    public function setTimePrepa(?\DateTimeInterface $timePrepa): self
    {
        $this->timePrepa = $timePrepa;

        return $this;
    }

    public function getPerson(): ?int
    {
        return $this->person;
    }

    public function setPerson(int $person): self
    {
        $this->person = $person;

        return $this;
    }

    /**
     * @return Collection|RecipeIngredient[]
     */
    public function getRecipeIngredients(): Collection
    {
        return $this->recipeIngredients;
    }

    public function addRecipeIngredient(RecipeIngredient $recipeIngredient): self
    {
        if (!$this->recipeIngredients->contains($recipeIngredient)) {
            $this->recipeIngredients[] = $recipeIngredient;
            $recipeIngredient->setRecipe($this);
        }

        return $this;
    }

    public function removeRecipeIngredient(RecipeIngredient $recipeIngredient): self
    {
        if ($this->recipeIngredients->contains($recipeIngredient)) {
            $this->recipeIngredients->removeElement($recipeIngredient);
            // set the owning side to null (unless already changed)
            if ($recipeIngredient->getRecipe() === $this) {
                $recipeIngredient->setRecipe(null);
            }
        }

        return $this;
    }

    public function getRecipePhoto()
    {
        return $this->recipePhoto;
    }

    public function setRecipePhoto($recipePhoto)
    {
        $this->recipePhoto = $recipePhoto;

        return $this;
    }

    public function getRecipeCategory(): ?RecipeCategory
    {
        return $this->recipeCategory;
    }

    public function setRecipeCategory(?RecipeCategory $recipeCategory): self
    {
        $this->recipeCategory = $recipeCategory;

        return $this;
    }

    public function getRecipeMenu(): ?RecipeMenu
    {
        return $this->recipeMenu;
    }

    public function setRecipeMenu(?RecipeMenu $recipeMenu): self
    {
        $this->recipeMenu = $recipeMenu;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->addRecipe($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
            $tag->removeRecipe($this);
        }

        return $this;
    }

    /**
     * @return Collection|MealPlan[]
     */
    public function getMealPlans(): Collection
    {
        return $this->mealPlans;
    }

    public function addMealPlan(MealPlan $mealPlan): self
    {
        if (!$this->mealPlans->contains($mealPlan)) {
            $this->mealPlans[] = $mealPlan;
            $mealPlan->addRecipe($this);
        }

        return $this;
    }

    public function removeMealPlan(MealPlan $mealPlan): self
    {
        if ($this->mealPlans->contains($mealPlan)) {
            $this->mealPlans->removeElement($mealPlan);
            $mealPlan->removeRecipe($this);
        }

        return $this;
    }
}
