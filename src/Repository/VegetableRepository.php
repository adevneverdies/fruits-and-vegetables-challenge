<?php

declare(strict_types=1);

namespace App\Repository;

use App\DTO\DTOInterface;
use App\DTO\VegetableDTO;
use App\Entity\EntityInterface;
use App\Entity\Vegetable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Vegetable>
 */
class VegetableRepository extends ServiceEntityRepository implements RepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vegetable::class);
    }

    /**
     * Filter fruits by name
     * @return EntityInterface[]|Vegetable[] Returns an array of Vegetable objects
     */
    public function filterByName(string $name): array
    {
        $queryBuilder = $this->createQueryBuilder('v');

        if (! empty($name)) {
            $queryBuilder->andWhere('v.name LIKE :name')
                ->setParameter('name', '%' . $name . '%');
        }

        return $queryBuilder->getQuery()->getResult();
    }

    public function create(DTOInterface $dto): EntityInterface
    {
        if (! $dto instanceof VegetableDTO) {
            throw new \InvalidArgumentException('Expected instance of VegetableDTO');
        }

        $vegetable = new Vegetable();

        $vegetable->setName($dto->getName());
        $vegetable->setQuantity($dto->getQuantity());
        $vegetable->setUnit($dto->getUnit());

        return $vegetable;
    }

    public function update(EntityInterface $vegetable, DTOInterface $dto): EntityInterface
    {
        if (! $dto instanceof VegetableDTO) {
            throw new \InvalidArgumentException('Expected instance of VegetableDTO');
        }

        if (! $vegetable instanceof Vegetable) {
            throw new \InvalidArgumentException('Expected instance of Vegetable');
        }
        $vegetable->setName($dto->getName());
        $vegetable->setQuantity($dto->getQuantity());
        $vegetable->setUnit($dto->getUnit());

        return $vegetable;
    }
}