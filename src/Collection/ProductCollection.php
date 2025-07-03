<?php

declare(strict_types=1);

namespace App\Collection;

use App\DTO\DTOInterface;
use App\DTO\FruitDTO;
use App\DTO\VegetableDTO;
use App\Entity\EntityInterface;
use App\Entity\Fruit;
use App\Entity\Product;
use App\Entity\Vegetable;
use App\Storage\DatabaseStorage;

class ProductCollection implements CollectionInterface
{
    /**
     * @var EntityInterface[]|Product[]
     */
    public array $collection = [];

    public function __construct(
        private DatabaseStorage $storage
    )
    {
        $this->collection = $this->storage->all(new Product());
    }

    /**
     * @throws \InvalidArgumentException | \RuntimeException
     */
    public function add(DTOInterface $dto): void
    {
        if (! $dto instanceof FruitDTO && ! $dto instanceof VegetableDTO) {
            throw new \InvalidArgumentException('Expected instance of FruitDTO or VegetableDTO');
        }

        $entityClass = $dto instanceof FruitDTO ? Fruit::class : Vegetable::class;

        $this->collection[] = $this->storage->save(new $entityClass(), $dto);
    }

    /**
     * @throws  \RuntimeException
     */
    public function remove(int $id): bool
    {
        $return = $this->storage->delete(new Product(), $id);

        if (! $return) {
            return false;
        }

        foreach ($this->collection as $key => $product) {
            if ($product->getId() === $id) {
                unset($this->collection[$key]);
            }
        }

        return true;
    }

    /**
     * @throws \RuntimeException
     * @return EntityInterface[]|Product[]
     */
    public function list(): array
    {
        $this->collection = $this->storage->all(new Product());
        return $this->collection;
    }

    /**
     * @throws \RuntimeException
     * @return EntityInterface[]
     */
    public function filterByName(string $name): array {
        $filtered = $this->storage->filterByName(new Product(), $name);
        return $filtered;
    }

    /**
     * @throws \RuntimeException
     */
    public function getById(int $id): ?EntityInterface {
        $entity = $this->storage->getById(new Product(), $id);

        if ($entity === null) {
            return null;
        }

        return $entity;
    }
}