<?php

namespace App\Entity;

use App\Repository\CarRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarRepository::class)]
class Car
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $colour = null;

    #[ORM\Column(length: 255)]
    private ?string $plate = null;

    #[ORM\Column(length: 255)]
    private ?string $vin = null;

    #[ORM\Column(length: 255)]
    private ?string $fuel = null;

    #[ORM\Column(nullable: true)]
    private ?int $cylinders = null;

    #[ORM\Column]
    private ?int $power = null;

    #[ORM\Column(nullable: true)]
    private ?int $kilometers = null;

    #[ORM\Column]
    private ?int $doors = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column(nullable: true)]
    private ?int $previous_owners = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $registration_date = null;

    #[ORM\Column]
    private ?bool $needs_repair = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $extras = null;

    #[ORM\ManyToOne(inversedBy: 'cars')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CarModel $carModel = null;

    #[ORM\Column(nullable: true)]
    private ?int $manufacture_year = null;

    /**
     * @param int|null $id
     * @param string|null $colour
     * @param string|null $plate
     * @param string|null $vin
     * @param string|null $fuel
     * @param int|null $cylinders
     * @param int|null $power
     * @param int|null $kilometers
     * @param int|null $doors
     * @param string|null $status
     * @param int|null $previous_owners
     * @param \DateTimeInterface|null $registration_date
     * @param bool|null $needs_repair
     * @param string|null $extras
     * @param CarModel|null $carModel
     * @param int|null $manufacture_year
     */
    public function __construct(?int $id = null, ?string $colour = null, ?string $plate = null, ?string $vin = null, ?string $fuel = null, ?int $cylinders = null, ?int $power = null, ?int $kilometers = null, ?int $doors = null, ?string $status = null, ?int $previous_owners = null, ?\DateTimeInterface $registration_date = null, ?bool $needs_repair = null, ?string $extras = null, ?CarModel $carModel = null, ?int $manufacture_year = null)
    {
        $this->id = $id;
        $this->colour = $colour;
        $this->plate = $plate;
        $this->vin = $vin;
        $this->fuel = $fuel;
        $this->cylinders = $cylinders;
        $this->power = $power;
        $this->kilometers = $kilometers;
        $this->doors = $doors;
        $this->status = $status;
        $this->previous_owners = $previous_owners;
        $this->registration_date = $registration_date;
        $this->needs_repair = $needs_repair;
        $this->extras = $extras;
        $this->carModel = $carModel;
        $this->manufacture_year = $manufacture_year;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getColour(): ?string
    {
        return $this->colour;
    }

    public function setColour(?string $colour): static
    {
        $this->colour = $colour;

        return $this;
    }

    public function getPlate(): ?string
    {
        return $this->plate;
    }

    public function setPlate(string $plate): static
    {
        $this->plate = $plate;

        return $this;
    }

    public function getVin(): ?string
    {
        return $this->vin;
    }

    public function setVin(string $vin): static
    {
        $this->vin = $vin;

        return $this;
    }

    public function getFuel(): ?string
    {
        return $this->fuel;
    }

    public function setFuel(string $fuel): static
    {
        $this->fuel = $fuel;

        return $this;
    }

    public function getCylinders(): ?int
    {
        return $this->cylinders;
    }

    public function setCylinders(?int $cylinders): static
    {
        $this->cylinders = $cylinders;

        return $this;
    }

    public function getPower(): ?int
    {
        return $this->power;
    }

    public function setPower(int $power): static
    {
        $this->power = $power;

        return $this;
    }

    public function getKilometers(): ?int
    {
        return $this->kilometers;
    }

    public function setKilometers(?int $kilometers): static
    {
        $this->kilometers = $kilometers;

        return $this;
    }

    public function getDoors(): ?int
    {
        return $this->doors;
    }

    public function setDoors(int $doors): static
    {
        $this->doors = $doors;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getPreviousOwners(): ?int
    {
        return $this->previous_owners;
    }

    public function setPreviousOwners(?int $previous_owners): static
    {
        $this->previous_owners = $previous_owners;

        return $this;
    }

    public function getRegistrationDate(): ?\DateTimeInterface
    {
        return $this->registration_date;
    }

    public function setRegistrationDate(\DateTimeInterface $registration_date): static
    {
        $this->registration_date = $registration_date;

        return $this;
    }

    public function isNeedsRepair(): ?bool
    {
        return $this->needs_repair;
    }

    public function setNeedsRepair(bool $needs_repair): static
    {
        $this->needs_repair = $needs_repair;

        return $this;
    }

    public function getExtras(): ?string
    {
        return $this->extras;
    }

    public function setExtras(?string $extras): static
    {
        $this->extras = $extras;

        return $this;
    }

    public function getCarModel(): ?CarModel
    {
        return $this->carModel;
    }

    public function setCarModel(?CarModel $carModel): static
    {
        $this->carModel = $carModel;

        return $this;
    }

    public function getManufactureYear(): ?int
    {
        return $this->manufacture_year;
    }

    public function setManufactureYear(?int $manufacture_year): static
    {
        $this->manufacture_year = $manufacture_year;

        return $this;
    }
}
