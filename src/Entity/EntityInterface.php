<?php

declare(strict_types=1);

namespace App\Entity;

interface EntityInterface
{
    public function getId(): ?int;

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;
}