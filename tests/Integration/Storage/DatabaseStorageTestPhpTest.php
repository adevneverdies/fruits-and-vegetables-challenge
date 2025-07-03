<?php

declare(strict_types=1);

namespace App\Tests\Integration\Storage;

use App\DTO\FruitDTO;
use App\Entity\Fruit;
use App\Tests\Integration\IntegrationTestCase;

class DatabaseStorageTestPhpTest extends IntegrationTestCase
{
    public function testSave(): void
    {

        $this->databaseStorage->save(new Fruit(), new FruitDTO('Banana', 10));

        $fruitRepository = $this->entityManager->getRepository(Fruit::class);
        $fruit = $fruitRepository->findAll();

        $this->assertCount(2, $fruit);
    }

    public function testUpdate(): void
    {
        $fruitRepository = $this->entityManager->getRepository(Fruit::class);
        $fruit = $fruitRepository->findOneBy(['name' => 'Apple']);

        $this->assertNotNull($fruit);
        $this->assertEquals(10.0, $fruit->getQuantity());

        $this->databaseStorage->update($fruit, new FruitDTO('Apple', 20.0));

        $updatedFruit = $fruitRepository->findOneBy(['name' => 'Apple']);
        $this->assertEquals(20.0, $updatedFruit->getQuantity());
    }
}
