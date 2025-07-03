<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\VegetableRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VegetableRepository::class)]
class Vegetable extends Product
{
    // Additional properties or methods specific to Vegetable can be added here
    // For example, you might want to add a property for the type of vegetable
    // #[ORM\Column(type: "string")]
    // private $type;

    // public function getType(): ?string
    // {
    //     return $this->type;
    // }

    // public function setType(string $type): self
    // {
    //     $this->type = $type;
    //     return $this;
    // }
}
