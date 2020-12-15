<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GiftRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=GiftRepository::class)
 */
class Gift
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid")
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidV4Generator::class)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\OneToMany(targetEntity=Stock::class, mappedBy="gift")
     */
    private $stock;

    /**
     * @ORM\ManyToMany(targetEntity=Receiver::class, mappedBy="gifts")
     */
    private $receivers;

    public function __construct()
    {
        $this->stock = new ArrayCollection();
        $this->receivers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection|Stock[]
     */
    public function getStock(): Collection
    {
        return $this->stock;
    }

    public function addStock(Stock $stock): self
    {
        if (!$this->stock->contains($stock)) {
            $this->stock[] = $stock;
            $stock->setGift($this);
        }

        return $this;
    }

    public function removeStock(Stock $stock): self
    {
        if ($this->stock->removeElement($stock)) {
            // set the owning side to null (unless already changed)
            if ($stock->getGift() === $this) {
                $stock->setGift(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Receiver[]
     */
    public function getReceivers(): Collection
    {
        return $this->receivers;
    }

    public function addReceiver(Receiver $receiver): self
    {
        if (!$this->receivers->contains($receiver)) {
            $this->receivers[] = $receiver;
            $receiver->addGift($this);
        }

        return $this;
    }

    public function removeReceiver(Receiver $receiver): self
    {
        if ($this->receivers->removeElement($receiver)) {
            $receiver->removeGift($this);
        }

        return $this;
    }
}
