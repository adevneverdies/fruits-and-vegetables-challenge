<?php

declare(strict_types=1);

namespace App\Storage;

use App\DTO\DTOInterface;
use App\Entity\EntityInterface;
use App\Repository\RepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class DatabaseStorage implements StorageInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    )
    {
    }

    /**
     * @throws \RuntimeException
     */
    public function save(EntityInterface $entity, DTOInterface $dto): EntityInterface
    {
        $repository = $this->entityManager->getRepository($entity::class);

        if (! $repository instanceof RepositoryInterface) {
            throw new \RuntimeException('Repository does not implement RepositoryInterface.');
        }

        $entity = $repository->create($dto);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }

    /**
     * @throws \RuntimeException
     */
    public function update(EntityInterface $entity, DTOInterface $dto): bool
    {
        $repository = $this->entityManager->getRepository($entity::class);

        if (! $repository instanceof RepositoryInterface) {
            throw new \RuntimeException('Repository does not implement RepositoryInterface.');
        }

        $entity = $repository->update($entity, $dto);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        return true;
    }

    /**
     * @throws \RuntimeException
     */
    public function delete(EntityInterface $entity): bool
    {
        if (is_null($entity->getId())) {
            throw new \RuntimeException('Entity does not have an ID, cannot delete.');
        }

        $this->entityManager->remove($entity);
        $this->entityManager->flush();
        return true;
    }
}