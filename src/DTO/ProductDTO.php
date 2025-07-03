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

    public function convertUnitToGrams(): void
    {
        if ($this->unit === 'kg') {
            $this->quantity *= 1000;
            $this->unit = 'g';
        } elseif ($this->unit === 'mg') {
            $this->quantity /= 1000;
            $this->unit = 'g';
        }
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

    /**
     * @return array<string>
     */
    public function validate(): array
    {
        $errors = [];
        if (empty($this->name)) {
            $errors[] = 'Name cannot be empty';
        }
        if ($this->quantity <= 0) {
            $errors[] = 'Quantity must be greater than zero';
        }
        if (! in_array($this->unit, ['g', 'kg', 'mg'])) {
             $errors[] = 'Unit must be one of: g, kg, mg';
        }

        return $errors;
    }
}