<?php

declare(strict_types=1);

namespace App\Collection;

use App\DTO\DTOInterface;
use App\DTO\FruitDTO;
use App\Entity\EntityInterface;
use App\Entity\Fruit;
use App\Storage\DatabaseStorage;

class FruitCollection implements CollectionInterface
{
    /**
     * @var EntityInterface[]|Fruit[]
     */
    public array $collection = [];

    public function __construct(
        private DatabaseStorage $storage
    )
    {
        $this->collection = $this->storage->all(new Fruit());
    }

    /**
     * @throws \InvalidArgumentException | \RuntimeException
     */
    public function add(DTOInterface $dto): void
    {
        if (! $dto instanceof FruitDTO) {
            throw new \InvalidArgumentException('Expected instance of FruitDTO');
        }

        $this->collection[] = $this->storage->save(new Fruit(), $dto);
    }

    /**
     * @throws  \RuntimeException
     */
    public function remove(int $id): bool
    {
        $return = $this->storage->delete(new Fruit(), $id);

        if (! $return) {
            return false;
        }

        foreach ($this->collection as $key => $fruit) {
            if ($fruit->getId() === $id) {
                unset($this->collection[$key]);
            }
        }

        return true;
    }

    /**
     * @throws \RuntimeException
     * @return EntityInterface[]|Fruit[]
     */
    public function list(): array
    {
        $this->collection = $this->storage->all(new Fruit());
        return $this->collection;
    }
}