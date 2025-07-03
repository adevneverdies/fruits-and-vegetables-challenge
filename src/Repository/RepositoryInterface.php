<?php

namespace App\Repository;

use App\DTO\DTOInterface;
use App\Entity\EntityInterface;

interface RepositoryInterface 
{
    public function create(DTOInterface $dto): EntityInterface;

    public function update(EntityInterface $entity, DTOInterface $dto): EntityInterface;
}