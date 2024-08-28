<?php

namespace App\Entity;

use App\Repository\CarBrandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarBrandRepository::class)]
class CarBrand
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logo = null;

    /**
     * @var Collection<int, CarModel>
     */
    #[ORM\OneToMany(targetEntity: CarModel::class, mappedBy: 'carBrand', orphanRemoval: true)]
    private Collection $carModels;

    #[ORM\Column]
    private ?bool $isDeleted = false;

    /**
     * @param int|null $id
     * @param string|null $name
     * @param string|null $logo
     */
    public function __construct(?int $id = null, ?string $name = null, ?string $logo = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->logo = $logo;
        $this->carModels = new ArrayCollection();
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

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): static
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * @return Collection<int, CarModel>
     */
    public function getCarModels(): Collection
    {
        return $this->carModels;
    }

    public function addCarModel(CarModel $carModel): static
    {
        if (!$this->carModels->contains($carModel)) {
            $this->carModels->add($carModel);
            $carModel->setCarBrand($this);
        }

        return $this;
    }

    public function removeCarModel(CarModel $carModel): static
    {
        if ($this->carModels->removeElement($carModel)) {
            // set the owning side to null (unless already changed)
            if ($carModel->getCarBrand() === $this) {
                $carModel->setCarBrand(null);
            }
        }

        return $this;
    }

    public function isDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setDeleted(bool $isDeleted): static
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
