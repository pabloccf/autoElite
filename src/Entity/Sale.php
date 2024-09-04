<?php

namespace App\Entity;

use App\Repository\SaleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SaleRepository::class)]
class Sale
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $sale_date = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $delivery_date = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $payment_form = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comments = null;

    #[ORM\ManyToOne(inversedBy: 'sales')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToOne(inversedBy: 'sale', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Car $car = null;

    /**
     * @param int|null $id
     * @param \DateTimeInterface|null $sale_date
     * @param float|null $price
     * @param \DateTimeInterface|null $delivery_date
     * @param string|null $payment_form
     * @param string|null $comments
     */
    public function __construct(?int $id = null, ?\DateTimeInterface $sale_date = null, ?float $price = null, ?\DateTimeInterface $delivery_date = null, ?string $payment_form = null, ?string $comments = null)
    {
        $this->id = $id;
        $this->sale_date = $sale_date;
        $this->price = $price;
        $this->delivery_date = $delivery_date;
        $this->payment_form = $payment_form;
        $this->comments = $comments;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSaleDate(): ?\DateTimeInterface
    {
        return $this->sale_date;
    }

    public function setSaleDate(\DateTimeInterface $sale_date): static
    {
        $this->sale_date = $sale_date;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getDeliveryDate(): ?\DateTimeInterface
    {
        return $this->delivery_date;
    }

    public function setDeliveryDate(?\DateTimeInterface $delivery_date): static
    {
        $this->delivery_date = $delivery_date;

        return $this;
    }

    public function getPaymentForm(): ?string
    {
        return $this->payment_form;
    }

    public function setPaymentForm(?string $payment_form): static
    {
        $this->payment_form = $payment_form;

        return $this;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(?string $comments): static
    {
        $this->comments = $comments;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getCar(): ?Car
    {
        return $this->car;
    }

    public function setCar(Car $car): static
    {
        $this->car = $car;

        return $this;
    }
}
