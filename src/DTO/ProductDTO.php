<?php

declare(strict_types=1);

namespace App\DTO;

class ProductDTO implements DTOInterface
{
    public function __construct(
        private string $name,
        private float $quantity,
        private string $unit = 'g'
    )
    {
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'quantity' => $this->quantity,
            'unit' => $this->unit,
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getQuantity(): float
    {
        return $this->quantity;
    }

    public function getUnit(): string
    {
        return $this->unit;
    }

    public function getType(): string
    {
        return 'product';
    }
}