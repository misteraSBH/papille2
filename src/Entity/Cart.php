<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CartRepository::class)
 */
class Cart
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=CartItem::class, mappedBy="cart", orphanRemoval=true)
     */
    private $cartitems;

    public function __construct()
    {
        $this->cartitems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|CartItem[]
     */
    public function getCartitems(): Collection
    {
        return $this->cartitems;
    }

    public function addCartitem(CartItem $cartitem): self
    {
        if (!$this->cartitems->contains($cartitem)) {
            $this->cartitems[] = $cartitem;
            $cartitem->setCart($this);
        }

        return $this;
    }

    public function removeCartitem(CartItem $cartitem): self
    {
        if ($this->cartitems->removeElement($cartitem)) {
            // set the owning side to null (unless already changed)
            if ($cartitem->getCart() === $this) {
                $cartitem->setCart(null);
            }
        }

        return $this;
    }
}
