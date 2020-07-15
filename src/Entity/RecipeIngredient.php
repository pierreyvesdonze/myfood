<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=RecipeIngredientRepository::class)
 */
class RecipeIngredient
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Recipe::class, inversedBy="recipeIngredients")
     * @ORM\JoinColumn(nullable=false)
     */
    private $recipe;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=1, nullable=true)
     */
    public $amount;

    /**
     * @ORM\Column(type="string", length=24, nullable=true)
     */
    public $unit;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(?Recipe $recipe): self
    {
        $this->recipe = $recipe;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(?int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(?string $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = strtolower($name);

        return $this;
    }

    public function convertAmountsAndUnits()
    {
        $amount = $this->getAmount();
        $unit = $this->getUnit();

        if ($amount > 999 && 'g' === $unit) {
            $amount /= 1000;
            $unit = 'kg';
        } elseif ($amount > 1000 && 'ml' === $unit) {
            $amount /= 1000;
            $unit = 'L';
        }
        $this->setAmount($amount);
        $this->setUnit($unit);

        return $this;
    }
}
