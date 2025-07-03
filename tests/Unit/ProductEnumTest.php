<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Enum\ProductEnum;
use PHPUnit\Framework\TestCase;

/**
 * @intternal
 * @covers ProductEnum
 */
class ProductEnumTest extends TestCase
{
    public function testEnumValues(): void
    {
        $this->assertSame('fruit', ProductEnum::FRUIT->value);
        $this->assertSame('vegetable', ProductEnum::VEGETABLE->value);
    }

    public function testEnumCases(): void
    {
        $this->assertContains(ProductEnum::FRUIT, ProductEnum::cases());
        $this->assertContains(ProductEnum::VEGETABLE, ProductEnum::cases());
    }

    public function testEnumType(): void
    {
        $this->assertSame('fruit', ProductEnum::FRUIT->value);
        $this->assertSame('vegetable', ProductEnum::VEGETABLE->value);
    }
}
