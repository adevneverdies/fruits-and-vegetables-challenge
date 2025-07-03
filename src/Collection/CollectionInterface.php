<?php

declare(strict_types=1);

namespace App\Collection;

use App\DTO\DTOInterface;
use App\Entity\EntityInterface;

interface CollectionInterface
{
    public function add(DTOInterface $dto): void;

    public function remove(int $id): bool;

    /**
     * @return EntityInterface[]
     */
    public function list(): array;

    /**
     * @throws \RuntimeException
     * @return EntityInterface[]
     */
    public function filterByName(string $name): array;

    /**
     * @throws \RuntimeException
     */
    public function getById(int $id): ?EntityInterface;
}