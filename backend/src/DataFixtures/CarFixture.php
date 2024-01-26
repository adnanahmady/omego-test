<?php

namespace App\DataFixtures;

use App\Entity\Car;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CarFixture extends BaseFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 10; ++$i) {
            $car = new Car();
            $car->setBrand((new BrandFixture())->createBrand($manager));
            $car->setModel($this->faker->name());
            $car->setColor((new ColorFixture())->createColor($manager));
            $manager->persist($car);
            $manager->flush();
        }
    }

    public function getDependencies(): array
    {
        return [
            BrandFixture::class,
            ColorFixture::class,
        ];
    }
}
