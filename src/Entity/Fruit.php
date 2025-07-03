<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\FruitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FruitRepository::class)]
class Fruit extends Product
{
    // Additional properties or methods specific to Fruit can be added here
    // For example, you might want to add a property for the color of the fruit
    // #[ORM\Column(type: "string")]
    // private $color;

    // public function getColor(): ?string
    // {
    //     return $this->color;
    // }

    // public function setColor(string $color): self
    // {
    //     $this->color = $color;
    //     return $this;
    // }
}