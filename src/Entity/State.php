<?php

namespace App\Entity;

use App\Repository\StateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StateRepository::class)]
class State
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $state = null;

    /**
     * @var Collection<int, ProductState>
     */
    #[ORM\OneToMany(targetEntity: ProductState::class, mappedBy: 'state')]
    private Collection $productStates;

    public function __construct()
    {
        $this->productStates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): static
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return Collection<int, ProductState>
     */
    public function getProductStates(): Collection
    {
        return $this->productStates;
    }

    public function addProductState(ProductState $productState): static
    {
        if (!$this->productStates->contains($productState)) {
            $this->productStates->add($productState);
            $productState->setState($this);
        }

        return $this;
    }

    public function removeProductState(ProductState $productState): static
    {
        if ($this->productStates->removeElement($productState)) {
            // set the owning side to null (unless already changed)
            if ($productState->getState() === $this) {
                $productState->setState(null);
            }
        }

        return $this;
    }
}
