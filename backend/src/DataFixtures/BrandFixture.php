<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use Doctrine\Persistence\ObjectManager;

class BrandFixture extends BaseFixture
{
    public function load(ObjectManager $manager): void
    {
        $brand = $this->createBrand($manager);
        $color = (new ColorFixture())->createColor($manager);
        $car = (new CarFixture())->createCar($brand, $color, $manager);
        $brand->addCar($car);
        $color->addCar($car);
        $this->addReference('brand', $brand);
    }

    public function createBrand(ObjectManager $manager): Brand
    {
        $brand = new Brand();
        $brand->setName($this->faker->name());
        $manager->persist($brand);
        $manager->flush();

        return $brand;
    }
}
