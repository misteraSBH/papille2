<?php

namespace App\Entity;

use App\Repository\BeverageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=BeverageRepository::class)
 */
class Beverage extends Product
{

    /**
     * @ORM\ManyToOne(targetEntity=Restaurant::class, inversedBy="beverages")
     * @Assert\Valid();
     */
    private $restaurant;



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

}
