<?php

namespace App\Entity;

use App\Repository\DishRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=DishRepository::class)
 */
class Dish
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Type("string")
     */
    private $name;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Assert\NotBlank()
     * @Assert\Type("numeric")
     * @Assert\Positive()
     */
    private $price = 0;

    /**
     * @ORM\ManyToOne(targetEntity=Restaurant::class, inversedBy="dishes")
     * @Assert\Valid();
     */
    private $restaurant;

    /**
     * @ORM\ManyToMany(targetEntity=MenuRestaurant::class, mappedBy="dishes")
     */
    private $menuRestaurants;

    public function __construct()
    {
        $this->menuRestaurants = new ArrayCollection();
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

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getRestaurant(): ?Restaurant
    {
        return $this->restaurant;
    }

    public function setRestaurant(?Restaurant $restaurant): self
    {
        $this->restaurant = $restaurant;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return Collection|MenuRestaurant[]
     */
    public function getMenuRestaurants(): Collection
    {
        return $this->menuRestaurants;
    }

    public function addMenuRestaurant(MenuRestaurant $menuRestaurant): self
    {
        if (!$this->menuRestaurants->contains($menuRestaurant)) {
            $this->menuRestaurants[] = $menuRestaurant;
            $menuRestaurant->addDish($this);
        }

        return $this;
    }

    public function removeMenuRestaurant(MenuRestaurant $menuRestaurant): self
    {
        if ($this->menuRestaurants->removeElement($menuRestaurant)) {
            $menuRestaurant->removeDish($this);
        }

        return $this;
    }
}
