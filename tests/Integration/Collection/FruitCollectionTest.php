<?php

namespace App\Tests\Integration\Collection;

use App\Collection\FruitCollection;
use App\Entity\Fruit;
use App\Tests\Integration\IntegrationTestCase;

class FruitCollectionTest extends IntegrationTestCase
{
    public function testListFruits(): void
    {
        $fruitCollection = new FruitCollection($this->databaseStorage);
        /**
         * @var Fruit[] $fruits
         */
        $fruits = $fruitCollection->list();

        $this->assertCount(1, $fruits); // Assuming there is one fruit in the fixture
        $this->assertEquals('Apple', $fruits[0]->getName());
    }

    public function testAddFruit(): void
    {
        $fruitCollection = new FruitCollection($this->databaseStorage);
        $fruitDTO = new \App\DTO\FruitDTO('Banana', 10);

        $fruitCollection->add($fruitDTO);
        /**
         * @var Fruit[] $fruits
         */
        $fruits = $fruitCollection->list();
        $this->assertCount(2, $fruits);
        $this->assertEquals('Banana', $fruits[1]->getName());
    }

    public function testRemoveFruit(): void
    {
        $fruitCollection = new FruitCollection($this->databaseStorage);

        $this->assertCount(1, $fruitCollection->collection);

        $this->assertTrue($fruitCollection->remove(1));

        $fruits = $fruitCollection->list();
        $this->assertCount(0, $fruits);
    }
}
