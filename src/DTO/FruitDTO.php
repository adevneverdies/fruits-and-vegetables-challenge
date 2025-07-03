<?php

declare(strict_types=1);

namespace App\DTO;

class FruitDTO extends ProductDTO
{
    public function getType(): string
    {
        return 'fruit';
    }
}