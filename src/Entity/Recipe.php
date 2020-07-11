<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="recipes")
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
     * @ORM\OneToMany(targetEntity=RecipeIngredient::class, mappedBy="recipe", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $recipeIngredients;

    /**
     * @ORM\OneToMany(targetEntity=RecipePhoto::class, mappedBy="recipe")
     */
    private $recipePhotos;

    /**
     * @ORM\ManyToOne(targetEntity=RecipeCategory::class, inversedBy="recipe")
     */
    private $recipeCategory;

    /**
     * @ORM\ManyToOne(targetEntity=RecipeType::class, inversedBy="recipe")
     * @ORM\JoinColumn(nullable=false)
     */
    private $recipeType;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, mappedBy="recipe")
     */
    private $tags;

    public function __construct()
    {
        $this->recipeSteps = new ArrayCollection();
        $this->recipeIngredients = new ArrayCollection();
        $this->recipePhotos = new ArrayCollection();
        $this->tags = new ArrayCollection();
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

    /**
     * @return Collection|RecipePhoto[]
     */
    public function getRecipePhotos(): Collection
    {
        return $this->recipePhotos;
    }

    public function addRecipePhoto(RecipePhoto $recipePhoto): self
    {
        if (!$this->recipePhotos->contains($recipePhoto)) {
            $this->recipePhotos[] = $recipePhoto;
            $recipePhoto->setRecipe($this);
        }

        return $this;
    }

    public function removeRecipePhoto(RecipePhoto $recipePhoto): self
    {
        if ($this->recipePhotos->contains($recipePhoto)) {
            $this->recipePhotos->removeElement($recipePhoto);
            // set the owning side to null (unless already changed)
            if ($recipePhoto->getRecipe() === $this) {
                $recipePhoto->setRecipe(null);
            }
        }

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

    public function getRecipeType(): ?RecipeType
    {
        return $this->recipeType;
    }

    public function setRecipeType(?RecipeType $recipeType): self
    {
        $this->recipeType = $recipeType;

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
}
