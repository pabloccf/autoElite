<?php

namespace App\Entity;

use App\Repository\CarModelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarModelRepository::class)]
class CarModel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $body_type = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    /**
     * @var Collection<int, Car>
     */
    #[ORM\OneToMany(targetEntity: Car::class, mappedBy: 'carModel', orphanRemoval: true)]
    private Collection $cars;

    #[ORM\ManyToOne(inversedBy: 'carModels')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CarBrand $carBrand = null;

    /**
     * @param int|null $id
     * @param string|null $name
     * @param string|null $body_type
     * @param string|null $image
     */
    public function __construct(?int $id = null, ?string $name = null, ?string $body_type = null, ?string $image = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->body_type = $body_type;
        $this->image = $image;
        $this->cars = new ArrayCollection();
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

    public function getBodyType(): ?string
    {
        return $this->body_type;
    }

    public function setBodyType(?string $body_type): static
    {
        $this->body_type = $body_type;

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
     * @return Collection<int, Car>
     */
    public function getCars(): Collection
    {
        return $this->cars;
    }

    public function addCar(Car $car): static
    {
        if (!$this->cars->contains($car)) {
            $this->cars->add($car);
            $car->setCarModel($this);
        }

        return $this;
    }

    public function removeCar(Car $car): static
    {
        if ($this->cars->removeElement($car)) {
            // set the owning side to null (unless already changed)
            if ($car->getCarModel() === $this) {
                $car->setCarModel(null);
            }
        }

        return $this;
    }

    public function getCarBrand(): ?CarBrand
    {
        return $this->carBrand;
    }

    public function setCarBrand(?CarBrand $carBrand): static
    {
        $this->carBrand = $carBrand;

        return $this;
    }
}
