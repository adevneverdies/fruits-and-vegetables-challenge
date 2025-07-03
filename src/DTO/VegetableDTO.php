<?php

declare(strict_types=1);

namespace App\DTO;

class VegetableDTO extends ProductDTO
{
    public function getType(): string
    {
        return 'vegetable';
    }
}