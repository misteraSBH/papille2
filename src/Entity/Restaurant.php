<?php

namespace App\Entity;

use App\Repository\RestaurantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @ORM\Entity(repositoryClass=RestaurantRepository::class)
 */
class Restaurant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=Dish::class, mappedBy="restaurant", cascade="persist")
     */
    private $dishes;

    /**
     * @ORM\OneToMany(targetEntity=Beverage::class, mappedBy="restaurant", cascade="persist")
     */
    private $beverages;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picture;

    /**
     * @ORM\OneToMany(targetEntity=Dessert::class, mappedBy="restaurant", cascade="persist")
     */
    private $desserts;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="restaurants", cascade="persist")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=MenuRestaurant::class, mappedBy="restaurant")
     */
    private $menusrestaurant;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $opening;

    /**
     * @ORM\OneToMany(targetEntity=SideDish::class, mappedBy="restaurant")
     */
    private $sideDishes;

    public function __construct()
    {
        $this->dishes = new ArrayCollection();
        $this->beverages = new ArrayCollection();
        $this->desserts = new ArrayCollection();
        $this->menusrestaurant = new ArrayCollection();
        $this->sideDishes = new ArrayCollection();
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

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
            $dish->setRestaurant($this);
        }

        return $this;
    }

    public function removeDish(Dish $dish): self
    {
        if ($this->dishes->removeElement($dish)) {
            // set the owning side to null (unless already changed)
            if ($dish->getRestaurant() === $this) {
                $dish->setRestaurant(null);
            }
        }

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
            $beverage->setRestaurant($this);
        }

        return $this;
    }

    public function removeBeverage(Beverage $beverage): self
    {
        if ($this->beverages->removeElement($beverage)) {
            // set the owning side to null (unless already changed)
            if ($beverage->getRestaurant() === $this) {
                $beverage->setRestaurant(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

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
            $dessert->setRestaurant($this);
        }

        return $this;
    }

    public function removeDessert(Dessert $dessert): self
    {
        if ($this->desserts->removeElement($dessert)) {
            // set the owning side to null (unless already changed)
            if ($dessert->getRestaurant() === $this) {
                $dessert->setRestaurant(null);
            }
        }

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

    /**
     * @return Collection|MenuRestaurant[]
     */
    public function getMenusrestaurant(): Collection
    {
        return $this->menusrestaurant;
    }

    public function addMenusrestaurant(MenuRestaurant $menusrestaurant): self
    {
        if (!$this->menusrestaurant->contains($menusrestaurant)) {
            $this->menusrestaurant[] = $menusrestaurant;
            $menusrestaurant->setRestaurant($this);
        }

        return $this;
    }

    public function removeMenusrestaurant(MenuRestaurant $menusrestaurant): self
    {
        if ($this->menusrestaurant->removeElement($menusrestaurant)) {
            // set the owning side to null (unless already changed)
            if ($menusrestaurant->getRestaurant() === $this) {
                $menusrestaurant->setRestaurant(null);
            }
        }

        return $this;
    }

    public function getOpening(): ?int
    {

        if($this->opening == null) {
            $this->opening = 1;
            return $this->opening;
        }

        return $this->opening;

    }

    public function setOpening(?int $opening): self
    {
        $this->opening = $opening;

        return $this;
    }

    /**
     * @return Collection|SideDish[]
     */
    public function getSideDishes(): Collection
    {
        return $this->sideDishes;
    }

    public function addSideDish(SideDish $sideDish): self
    {
        if (!$this->sideDishes->contains($sideDish)) {
            $this->sideDishes[] = $sideDish;
            $sideDish->setRestaurant($this);
        }

        return $this;
    }

    public function removeSideDish(SideDish $sideDish): self
    {
        if ($this->sideDishes->removeElement($sideDish)) {
            // set the owning side to null (unless already changed)
            if ($sideDish->getRestaurant() === $this) {
                $sideDish->setRestaurant(null);
            }
        }

        return $this;
    }
}
