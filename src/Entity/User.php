<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Role", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $role;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ShoppingList", mappedBy="user", fetch="EAGER")
     */
    private $shoppingLists;

    /**
     * @ORM\OneToMany(targetEntity=Recipe::class, mappedBy="user", fetch="EAGER")
     */
    private $recipies;

    public function __construct()
    {
        $this->shoppingLists = new ArrayCollection();
        $this->recipies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of roles.
     */
    public function getRolesCollection()
    {
        return $this->roles;
    }

    public function getRoles()
    {
        return [$this->getRole()->getRoleString()];
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return Collection|ShoppingList[]
     */
    public function getShoppingLists(): Collection
    {
        return $this->shoppingLists;
    }

    public function addShoppingList(ShoppingList $shoppingList): self
    {
        if (!$this->shoppingLists->contains($shoppingList)) {
            $this->shoppingLists[] = $shoppingList;
        }

        return $this;
    }

    /**
     * @return Collection|Recipe[]
     */
    public function getRecipies(): Collection
    {
        return $this->recipies;
    }

    public function addRecipe(Recipe $recipe): self
    {
        if (!$this->recipies->contains($recipe)) {
            $this->recipies[] = $recipe;
            $recipe->setUser($this);
        }

        return $this;
    }

    public function removeRecipe(Recipe $recipe): self
    {
        if ($this->recipies->contains($recipe)) {
            $this->recipies->removeElement($recipe);
            // set the owning side to null (unless already changed)
            if ($recipe->getUser() === $this) {
                $recipe->setUser(null);
            }
        }

        return $this;
    }
}
