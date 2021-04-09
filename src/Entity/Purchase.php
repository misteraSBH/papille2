<?php

namespace App\Entity;

use App\Repository\PurchaseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PurchaseRepository::class)
 */
class Purchase
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
    private $idPurchase;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $amount;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $paymentMethod;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity=PurchaseItem::class, mappedBy="purchase", orphanRemoval=true, cascade={"persist"})
     */
    private $purchaseItems;

    /**
     * @ORM\OneToMany(targetEntity=OrderSlip::class, mappedBy="purchase", orphanRemoval=true)
     */
    private $orderSlips;

    public function __construct()
    {
        $this->purchaseItems = new ArrayCollection();
        $this->orderSlips = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdPurchase(): ?string
    {
        return $this->idPurchase;
    }

    public function setIdPurchase(string $idPurchase): self
    {
        $this->idPurchase = $idPurchase;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getPaymentMethod(): ?int
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(?int $paymentMethod): self
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection|PurchaseItem[]
     */
    public function getPurchaseItems(): Collection
    {
        return $this->purchaseItems;
    }

    public function addPurchaseItem(PurchaseItem $purchaseItem): self
    {
        if (!$this->purchaseItems->contains($purchaseItem)) {
            $this->purchaseItems[] = $purchaseItem;
            $purchaseItem->setPurchase($this);
        }

        return $this;
    }

    public function removePurchaseItem(PurchaseItem $purchaseItem): self
    {
        if ($this->purchaseItems->removeElement($purchaseItem)) {
            // set the owning side to null (unless already changed)
            if ($purchaseItem->getPurchase() === $this) {
                $purchaseItem->setPurchase(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|OrderSlip[]
     */
    public function getOrderSlips(): Collection
    {
        return $this->orderSlips;
    }

    public function addOrderSlip(OrderSlip $orderSlip): self
    {
        if (!$this->orderSlips->contains($orderSlip)) {
            $this->orderSlips[] = $orderSlip;
            $orderSlip->setPurchase($this);
        }

        return $this;
    }

    public function removeOrderSlip(OrderSlip $orderSlip): self
    {
        if ($this->orderSlips->removeElement($orderSlip)) {
            // set the owning side to null (unless already changed)
            if ($orderSlip->getPurchase() === $this) {
                $orderSlip->setPurchase(null);
            }
        }

        return $this;
    }
}
