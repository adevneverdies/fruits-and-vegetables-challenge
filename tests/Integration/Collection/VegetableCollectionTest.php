<?php

namespace App\Tests\Integration\Collection;

use App\Collection\VegetableCollection;
use App\Entity\Vegetable;
use App\Tests\Integration\IntegrationTestCase;

class VegetableCollectionTest extends IntegrationTestCase
{
    public function testListVegetables(): void
    {
        $vegetableCollection = new VegetableCollection($this->databaseStorage);
        /**
         * @var Vegetable[] $vegetables
         */
        $vegetables = $vegetableCollection->list();

        $this->assertCount(1, $vegetables); // Assuming there is one Vegetable in the fixture
        $this->assertEquals('Carrot', $vegetables[0]->getName());
    }

    public function testAddVegetable(): void
    {
        $vegetableCollection = new VegetableCollection($this->databaseStorage);
        $vegetableDTO = new \App\DTO\VegetableDTO('Potato', 100);

        $vegetableCollection->add($vegetableDTO);

         /**
         * @var Vegetable[] $vegetables
         */
        $vegetables = $vegetableCollection->list();
        $this->assertCount(2, $vegetables);
        $this->assertEquals('Potato', $vegetables[1]->getName());
    }

    public function testRemoveVegetable(): void
    {
        $vegetableCollection = new VegetableCollection($this->databaseStorage);

        $this->assertCount(1, $vegetableCollection->collection);

        $this->assertTrue($vegetableCollection->remove(2));

        $vegetables = $vegetableCollection->list();
        $this->assertCount(0, $vegetables);
    }
}
