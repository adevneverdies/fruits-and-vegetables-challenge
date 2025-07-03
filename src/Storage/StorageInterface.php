<?php

declare(strict_types=1);

namespace App\Storage;

use App\DTO\DTOInterface;
use App\Entity\EntityInterface;

interface StorageInterface
{
    /**
     * @throws \RuntimeException
     */
    public function save(EntityInterface $entity, DTOInterface $dto): EntityInterface;

    /**
     * @throws \RuntimeException
     */
    public function update(EntityInterface $entity, DTOInterface $dto): bool;

    /**
     * @throws \RuntimeException
     */
    public function delete(EntityInterface $entity, int $id): bool;

    /**
     * @return EntityInterface[]
     * @throws \RuntimeException
     */
    public function all(EntityInterface $entity): array;

    /**
     * @throws \RuntimeException
     */
    public function getById(EntityInterface $entity, int $id): ?EntityInterface;

    /**
     * @return EntityInterface[]
     * @throws \RuntimeException
     */
    public function filterByName(EntityInterface $entity, string $name): array;
}