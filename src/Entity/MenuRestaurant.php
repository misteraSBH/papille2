<?php

namespace App\Entity;

use App\Repository\MenuRestaurantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MenuRestaurantRepository::class)
 */
class MenuRestaurant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Dish::class, inversedBy="menuRestaurants")
     */
    private $dishes;

    /**
     * @ORM\ManyToOne(targetEntity=Restaurant::class, inversedBy="menusrestaurant")
     * @ORM\JoinColumn(nullable=false)
     */
    private $restaurant;

    /**
     * @ORM\ManyToMany(targetEntity=Beverage::class, inversedBy="menuRestaurants")
     */
    private $beverages;

    /**
     * @ORM\ManyToMany(targetEntity=Dessert::class, inversedBy="menuRestaurants")
     */
    private $desserts;



    public function __construct()
    {
        $this->dishes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Dish[]
     */
    public function getDishes(): Collection
    {
        return $this->dishes;
    }

    public function addDish(Dish $dish): self
    {
        if (!$this->dishes->contains($dish)) {
            $this->dishes[] = $dish;
        }

        return $this;
    }

    public function removeDish(Dish $dish): self
    {
        $this->dishes->removeElement($dish);

        return $this;
    }

    /**
     * @return Collection|Beverage[]
     */
    public function getBeverages(): Collection
    {
        return $this->beverages;
    }

    public function addBeverage(Beverage $beverage): self
    {
        if (!$this->beverages->contains($beverage)) {
            $this->beverages[] = $beverage;
        }

        return $this;
    }

    public function removeBeverage(Beverage $beverage): self
    {
        $this->beverages->removeElement($beverage);

        return $this;
    }


    /**
     * @return Collection|Dessert[]
     */
    public function getDesserts(): Collection
    {
        return $this->desserts;
    }

    public function addDessert(Dessert $dessert): self
    {
        if (!$this->desserts->contains($dessert)) {
            $this->desserts[] = $dessert;
        }

        return $this;
    }

    public function removeDessert(Dessert $dessert): self
    {
        $this->desserts->removeElement($dessert);

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

}
