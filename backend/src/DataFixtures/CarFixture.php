<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use App\Entity\Car;
use App\Entity\Color;
use Doctrine\Persistence\ObjectManager;

class CarFixture extends BaseFixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 10; ++$i) {
            $brand = (new BrandFixture())->createBrand($manager);
            $color = (new ColorFixture())->createColor($manager);
            $car = $this->createCar($brand, $color, $manager);
            for ($j = 0; $j < 5; ++$j) {
                (new ReviewFixture())->createReview($manager, $car);
            }
        }
    }

    public function createCar(
        Brand $brand,
        Color $color,
        ObjectManager $manager
    ): Car {
        $car = new Car();
        $car->setBrand($brand);
        $car->setModel($this->faker->name());
        $car->setColor($color);
        $manager->persist($car);
        $manager->flush();

        return $car;
    }
}
