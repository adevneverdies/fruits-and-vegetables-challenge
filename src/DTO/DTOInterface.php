<?php

declare(strict_types=1);

namespace App\DTO;

interface DTOInterface
{
    /**
     * Converts the DTO to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array;

    /**
     * Converts the DTO to a JSON string.
     *
     * @return string
     */
    public function toJson(): string;
}