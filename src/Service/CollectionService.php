<?php

declare(strict_types=1);

namespace App\Service;

use App\Collection\FruitCollection;
use App\Collection\ProductCollection;
use App\Collection\VegetableCollection;
use App\DTO\FruitDTO;
use App\DTO\ProductDTO;
use App\DTO\VegetableDTO;
use App\Entity\EntityInterface;
use App\Entity\Fruit;
use App\Entity\Product;
use App\Entity\Vegetable;
use App\Enum\ProductEnum;
use App\Util\FileReader\FileReaderFactory;

class CollectionService
{
    public function __construct(
        private ProductCollection $productCollection,
        private FruitCollection $fruitCollection,
        private VegetableCollection $vegetableCollection
    ) {
    }

    public function getProductCollection(): ProductCollection
    {
        return $this->productCollection;
    }

    public function getFruitCollection(): FruitCollection
    {
        return $this->fruitCollection;
    }

    public function getVegetableCollection(): VegetableCollection
    {
        return $this->vegetableCollection;
    }

    public function addProduct(ProductDTO $dto): void
    {
        $this->productCollection->add($dto);
    }

    public function addFruit(FruitDTO $dto): void
    {
        $this->fruitCollection->add($dto);
    }

    public function addVegetable(VegetableDTO $dto): void
    {
        $this->vegetableCollection->add($dto);
    }

    public function removeProduct(int $id): bool
    {
        return $this->productCollection->remove($id);
    }

    public function removeFruit(int $id): bool
    {
        return $this->fruitCollection->remove($id);
    }

    public function removeVegetable(int $id): bool
    {
        return $this->vegetableCollection->remove($id);
    }

    /**
     * @return array<EntityInterface|Product>
     * @throws \RuntimeException
     */
    public function listProducts(): array
    {
        return $this->productCollection->list();
    }

    /**
     * @return array<EntityInterface|Fruit>
     * @throws \RuntimeException
     */
    public function listFruits(): array
    {
        return $this->fruitCollection->list();
    }

    /**
     * @return array<EntityInterface|Vegetable>
     * @throws \RuntimeException
     */
    public function listVegetables(): array
    {
        return $this->vegetableCollection->list();
    }

    /**
     * @return array<EntityInterface|Product>
     * @throws \RuntimeException
     */
    public function filterProductsByName(string $name): array
    {
        return $this->productCollection->filterByName($name);
    }

    /**
     * @return array<EntityInterface|Fruit>
     * @throws \RuntimeException
     */
    public function filterFruitsByName(string $name): array
    {
        return $this->fruitCollection->filterByName($name);
    }

    /**
     * @return array<EntityInterface|Vegetable>
     * @throws \RuntimeException
     */
    public function filterVegetablesByName(string $name): array
    {
        return $this->vegetableCollection->filterByName($name);
    }

    public function getProductById(int $id): ?EntityInterface
    {
        return $this->productCollection->getById($id);
    }

    public function getFruitById(int $id): ?EntityInterface
    {
        return $this->fruitCollection->getById($id);
    }

    public function getVegetableById(int $id): ?EntityInterface
    {
        return $this->vegetableCollection->getById($id);
    }

    /**
     * @return array<EntityInterface|Product>
     * @throws \RuntimeException|\JsonException|\InvalidArgumentException
     */
    public function addFromFile(string $filepath, string $fileType = 'json'): array {
        $reader = FileReaderFactory::create($fileType);
        $readData = $reader->read($filepath);
        $listDto = [];
        foreach ($readData as $data) {
            if (! isset($data['type'])) {
                throw new \RuntimeException('Type is missing.');
            }

            $type = ProductEnum::tryFrom($data['type']);

            if (! $type ) {
                throw new \InvalidArgumentException('Type is not valid: ' . $data['type']);
            }

            $dtoClass = ($type === ProductEnum::FRUIT) ? FruitDTO::class : VegetableDTO::class;

            $dto = new $dtoClass(
                $data['name'] ?? '',
                $data['quantity'] ?? 0,
                $data['unit'] ?? 'g'
            );

            $errors = $dto->validate();
            if (count($errors) > 0) {
                throw new \InvalidArgumentException('errors: ' . json_encode($errors));
            }

            $dto->convertUnitToGrams();
            $listDto[] = $dto;
        }

        foreach($listDto as $dto) {
            $this->addProduct($dto);
        }

        return $this->listProducts();
    }
}