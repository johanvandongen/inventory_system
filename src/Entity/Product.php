<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column(nullable: true)]
    private ?float $price_bought = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_bought = null;

    /**
     * @var Collection<int, Category>
     */
    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'product')]
    private Collection $category;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    /**
     * @var Collection<int, ProductState>
     */
    #[ORM\OneToMany(targetEntity: ProductState::class, mappedBy: 'product', orphanRemoval: true)]
    private Collection $state;

    public function __construct()
    {
        $this->category = new ArrayCollection();
        $this->state = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPriceBought(): ?float
    {
        return $this->price_bought;
    }

    public function setPriceBought(?float $price_bought): static
    {
        $this->price_bought = $price_bought;

        return $this;
    }

    public function getDateBought(): ?\DateTimeInterface
    {
        return $this->date_bought;
    }

    public function setDateBought(?\DateTimeInterface $date_bought): static
    {
        $this->date_bought = $date_bought;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->category->contains($category)) {
            $this->category->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        $this->category->removeElement($category);

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, ProductState>
     */
    public function getState(): Collection
    {
        return $this->state;
    }

    public function addState(ProductState $state): static
    {
        if (!$this->state->contains($state)) {
            $this->state->add($state);
            $state->setProduct($this);
        }

        return $this;
    }

    public function removeState(ProductState $state): static
    {
        if ($this->state->removeElement($state)) {
            // set the owning side to null (unless already changed)
            if ($state->getProduct() === $this) {
                $state->setProduct(null);
            }
        }

        return $this;
    }
}
