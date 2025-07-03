<?php

declare(strict_types=1);

namespace App\Repository;

use App\DTO\DTOInterface;
use App\DTO\FruitDTO;
use App\Entity\EntityInterface;
use App\Entity\Fruit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Fruit>
 */
class FruitRepository extends ServiceEntityRepository implements RepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fruit::class);
    }

    /**
     * Filter fruits by name
     * @return EntityInterface[]|Fruit[] Returns an array of Fruit objects
     */
    public function filterByName(string $name): array
    {
        $queryBuilder = $this->createQueryBuilder('f');

        if (! empty($name)) {
            $queryBuilder->andWhere('f.name LIKE :name')
                ->setParameter('name', '%' . $name . '%');
        }

        return $queryBuilder->getQuery()->getResult();
    }

    public function create(DTOInterface $dto): EntityInterface
    {
        if (! $dto instanceof FruitDTO) {
            throw new \InvalidArgumentException('Expected instance of FruitDTO');
        }

        $fruit = new Fruit();

        $fruit->setName($dto->getName());
        $fruit->setQuantity($dto->getQuantity());
        $fruit->setUnit($dto->getUnit());

        return $fruit;
    }

    public function update(EntityInterface $fruit, DTOInterface $dto): EntityInterface
    {
         if (! $dto instanceof FruitDTO) {
            throw new \InvalidArgumentException('Expected instance of FruitDTO');
        }
        if (! $fruit instanceof Fruit) {
            throw new \InvalidArgumentException('Expected instance of Fruit');
        }
        $fruit->setName($dto->getName());
        $fruit->setQuantity($dto->getQuantity());
        $fruit->setUnit($dto->getUnit());

        return $fruit;
    }
}