<?php

declare(strict_types=1);

namespace App\Tests\Integration\Fixture;

use App\Entity\Fruit;
use App\Entity\Vegetable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DataFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $fruit = new Fruit();
        $fruit->setName('Apple');
        $fruit->setQuantity(10.0);
        $manager->persist($fruit);

        $vegetable = new Vegetable();
        $vegetable->setName('Carrot');
        $vegetable->setQuantity(5.0);
        $manager->persist($vegetable);

        $manager->flush();
    }
}