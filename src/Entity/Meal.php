<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MealRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MealRepository::class)
 */
class Meal
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255)
     */
    private $category;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255)
     */
    private $area;

    /**
     * @Assert\NotBlank
     * @Assert\Url
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity=Instruction::class, mappedBy="meal", orphanRemoval=true)
     */
    private $instructions;

    /**
     * @ORM\OneToMany(targetEntity=Ingredient::class, mappedBy="meal", orphanRemoval=true)
     */
    private $ingredients;

    public function __construct()
    {
        $this->instructions = new ArrayCollection();
        $this->ingredients = new ArrayCollection();
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

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getArea(): ?string
    {
        return $this->area;
    }

    public function setArea(string $area): self
    {
        $this->area = $area;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection|Instruction[]
     */
    public function getInstructions(): Collection
    {
        return $this->instructions;
    }

    public function addInstruction(Instruction $instruction): self
    {
        if (!$this->instructions->contains($instruction)) {
            $this->instructions[] = $instruction;
            $instruction->setMeal($this);
        }

        return $this;
    }

    public function removeInstruction(Instruction $instruction): self
    {
        if ($this->instructions->removeElement($instruction)) {
            // set the owning side to null (unless already changed)
            if ($instruction->getMeal() === $this) {
                $instruction->setMeal(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Ingredient[]
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredient $ingredient): self
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients[] = $ingredient;
            $ingredient->setMeal($this);
        }

        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): self
    {
        if ($this->ingredients->removeElement($ingredient)) {
            // set the owning side to null (unless already changed)
            if ($ingredient->getMeal() === $this) {
                $ingredient->setMeal(null);
            }
        }

        return $this;
    }
}
