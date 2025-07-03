<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Storage\DatabaseStorage;
use App\Tests\Integration\Fixture\DataFixture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

abstract class IntegrationTestCase extends KernelTestCase
{
    use ResetDatabase; use Factories;

    protected ?EntityManagerInterface $entityManager;

    protected DatabaseStorage $databaseStorage;

    public function setUp(): void
    {
        self::bootKernel();

        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->databaseStorage = new DatabaseStorage($this->entityManager);

        $fixture = new DataFixture();
        $fixture->load($this->entityManager);
    }

    public function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null; // Avoid memory leaks
    }
}
