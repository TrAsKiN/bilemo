<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Attributes as OA;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\Table(name: '`products`')]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[OA\Property(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[OA\Property(type: 'string', maxLength: 255)]
    private ?string $brand = null;

    #[ORM\Column(length: 255)]
    #[OA\Property(type: 'string', maxLength: 255)]
    private ?string $model = null;

    #[ORM\Column(type: Types::TEXT)]
    #[OA\Property(type: 'string')]
    private ?string $description = null;

    #[ORM\Column]
    #[OA\Property(type: 'number')]
    private ?float $price = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
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
}
