<?php

declare(strict_types=1);

namespace App\Repository;

use App\DTO\DTOInterface;
use App\DTO\ProductDTO;
use App\Entity\EntityInterface;
use App\Entity\Fruit;
use App\Entity\Product;
use App\Entity\Vegetable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @return Array<Vegetable|Fruit> Returns an array of Vegetable or Fruit objects
     */
    public function filterByName(string $name): array
    {
        $queryBuilder = $this->createQueryBuilder('p');

        if (! empty($name)) {
            $queryBuilder->andWhere('v.name LIKE :name')
                ->setParameter('name', '%' . $name . '%');
        }

        return $queryBuilder->getQuery()->getResult();
    }

    public function create(DTOInterface $dto): EntityInterface
    {
        if (! $dto instanceof ProductDTO) {
            throw new \InvalidArgumentException('Expected instance of ProductDTO');
        }

        $product = match ($dto->getType()) {
            'fruit' => new Fruit(),
            'vegetable' => new Vegetable(),
            default => throw new \InvalidArgumentException('Invalid product type'),
        };

        $product->setName($dto->getName());
        $product->setQuantity($dto->getQuantity());
        $product->setUnit($dto->getUnit());

        return $product;
    }

    public function update(EntityInterface $product, DTOInterface $dto): EntityInterface
    {
        if (! $dto instanceof ProductDTO) {
            throw new \InvalidArgumentException('Expected instance of ProductDTO');
        }

        if (! $product instanceof Product) {
            throw new \InvalidArgumentException('Expected instance of Product');
        }

        $product->setName($dto->getName());
        $product->setQuantity($dto->getQuantity());
        $product->setUnit($dto->getUnit());

        return $product;
    }
}