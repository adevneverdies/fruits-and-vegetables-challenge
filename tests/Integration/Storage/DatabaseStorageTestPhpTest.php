<?php

declare(strict_types=1);

namespace App\Tests\Integration\Storage;

use App\DTO\FruitDTO;
use App\Entity\Fruit;
use App\Storage\DatabaseStorage;
use App\Tests\Integration\Fixture\DataFixture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class DatabaseStorageTestPhpTest extends KernelTestCase
{
    use ResetDatabase; use Factories;

    private ?EntityManagerInterface $entityManager;

    private DatabaseStorage $databaseStorage;

    public function setUp(): void
    {
        self::bootKernel();

        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->databaseStorage = new DatabaseStorage($this->entityManager);

        $fixture = new DataFixture();
        $fixture->load($this->entityManager);
    }

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

    public function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null; // Avoid memory leaks
    }
}
