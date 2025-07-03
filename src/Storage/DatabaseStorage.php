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
    public function delete(EntityInterface $entity, int $id): bool
    {
        $entity = $this->getById($entity, $id);

        if ($entity === null) {
            throw new \RuntimeException('Entity not found for deletion.');
        }

        $this->entityManager->remove($entity);
        $this->entityManager->flush();
        return true;
    }

    /**
     * @return EntityInterface[]
     * @throws \RuntimeException
     */
    public function all(EntityInterface $entity): array
    {
        $repository = $this->entityManager->getRepository($entity::class);

        if (! $repository instanceof RepositoryInterface) {
            throw new \RuntimeException('Repository does not implement RepositoryInterface.');
        }

        return $repository->findAll();
    }

    /**
     * @throws \RuntimeException
     */
    public function getById(EntityInterface $entity, int $id): ?EntityInterface {
        $repository = $this->entityManager->getRepository($entity::class);

        if (! $repository instanceof RepositoryInterface) {
            throw new \RuntimeException('Repository does not implement RepositoryInterface.');
        }

        return $repository->find($id);
    }

    /**
     * @return EntityInterface[]
     * @throws \RuntimeException
     */
    public function filterByName(EntityInterface $entity, string $name): array {
        $repository = $this->entityManager->getRepository($entity::class);

        if (! $repository instanceof RepositoryInterface) {
            throw new \RuntimeException('Repository does not implement RepositoryInterface.');
        }

        return $repository->filterByName($name);
    }
}