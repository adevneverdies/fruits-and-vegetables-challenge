<?php

declare(strict_types=1);

namespace App\Collection;

use App\DTO\DTOInterface;
use App\DTO\VegetableDTO;
use App\Entity\EntityInterface;
use App\Entity\Vegetable;
use App\Storage\DatabaseStorage;

class VegetableCollection implements CollectionInterface
{
    /**
     * @var EntityInterface[]|Vegetable[]
     */
    public array $collection = [];

    public function __construct(
        private DatabaseStorage $storage
    )
    {
        $this->collection = $this->storage->all(new Vegetable());
    }

    /**
     * @throws \InvalidArgumentException | \RuntimeException
     */
    public function add(DTOInterface $dto): void
    {
        if (! $dto instanceof VegetableDTO) {
            throw new \InvalidArgumentException('Expected instance of VegetableDTO');
        }

        $this->collection[] = $this->storage->save(new Vegetable(), $dto);
    }

    /**
     * @throws  \RuntimeException
     */
    public function remove(int $id): bool
    {
        $return = $this->storage->delete(new Vegetable(), $id);

        if (! $return) {
            return false;
        }

        foreach ($this->collection as $key => $vegetable) {
            if ($vegetable->getId() === $id) {
                unset($this->collection[$key]);
            }
        }

        return true;
    }

    /**
     * @throws \RuntimeException
     * @return EntityInterface[]|Vegetable[]
     */
    public function list(): array
    {
        $this->collection = $this->storage->all(new Vegetable());
        return $this->collection;
    }
}