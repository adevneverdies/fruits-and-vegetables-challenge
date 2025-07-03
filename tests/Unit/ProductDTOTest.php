<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\DTO\DTOInterface;
use App\DTO\FruitDTO;
use App\DTO\ProductDTO;
use App\DTO\VegetableDTO;
use PHPUnit\Framework\TestCase;

/**
 * @intternal
 * @covers FruitDTO
 * @covers ProductDTO
 * @covers VegetableDTO
 */
class FruitDTOTest extends TestCase
{
    public function testInstanceOfProductDTO(): void
    {
        $fruitDTO = new FruitDTO('Apple', 10);
        $this->assertInstanceOf(ProductDTO::class, $fruitDTO);
        $this->assertInstanceOf(DTOInterface::class, $fruitDTO);

        $vegetableDTO = new VegetableDTO('Carrot', 5);
        $this->assertInstanceOf(ProductDTO::class, $vegetableDTO);
        $this->assertInstanceOf(DTOInterface::class, $vegetableDTO);
    }

    public function testToArray(): void
    {
        $fruitDTO = new FruitDTO('Apple', 10);
        $expectedArray = [
            'name' => 'Apple',
            'quantity' => 10.0,
            'unit' => 'g',
        ];

        $this->assertSame($expectedArray, $fruitDTO->toArray());

        $vegetableDTO = new VegetableDTO('Carrot', 5);
        $expectedArray = [
            'name' => 'Carrot',
            'quantity' => 5.0,
            'unit' => 'g',
        ];
        $this->assertSame($expectedArray, $vegetableDTO->toArray());
    }

    public function testToJson(): void
    {
        $fruitDTO = new FruitDTO('Apple', 10);
        $expectedJson = json_encode([
            'name' => 'Apple',
            'quantity' => 10.0,
            'unit' => 'g',
        ]);

        $this->assertSame($expectedJson, $fruitDTO->toJson());

        $vegetableDTO = new VegetableDTO('Carrot', 5);
        $expectedJson = json_encode([
            'name' => 'Carrot',
            'quantity' => 5.0,
            'unit' => 'g',
        ]);
        $this->assertSame($expectedJson, $vegetableDTO->toJson());
    }

    public function testGetFunction(): void
    {
        $fruitDTO = new FruitDTO('Apple', 10);
        $this->assertSame('Apple', $fruitDTO->getName());
        $this->assertSame(10.0, $fruitDTO->getQuantity());
        $this->assertSame('g', $fruitDTO->getUnit());
        $this->assertSame('fruit', $fruitDTO->getType());

        $vegetableDTO = new VegetableDTO('Carrot', 5);
        $this->assertSame('Carrot', $vegetableDTO->getName());
        $this->assertSame(5.0, $vegetableDTO->getQuantity());
        $this->assertSame('g', $vegetableDTO->getUnit());
        $this->assertSame('vegetable', $vegetableDTO->getType());

        $productDTO = new ProductDTO('Generic Product', 20);
        $this->assertSame('Generic Product', $productDTO->getName());
        $this->assertSame(20.0, $productDTO->getQuantity());
        $this->assertSame('g', $productDTO->getUnit());
        $this->assertSame('product', $productDTO->getType());
    }
}
