<?php

namespace App\DataFixtures;

use App\Entity\Car;
use App\Entity\Review;
use Doctrine\Persistence\ObjectManager;

class ReviewFixture extends BaseFixture
{
    public function load(ObjectManager $manager): void
    {
    }

    public function createReview(
        ObjectManager $manager,
        Car $car = null,
        int $minRate = 1,
        int $maxRate = 10
    ): Review {
        $review = new Review();
        $review->setRate($this->faker->numberBetween(min: $minRate, max: $maxRate));
        $review->setContent($this->faker->name);
        $review->setCar($car ?: (new CarFixture())->createCar(
            (new BrandFixture())->createBrand($manager),
            (new ColorFixture())->createColor($manager),
            $manager
        ));
        $manager->persist($review);
        $manager->flush();

        return $review;
    }
}
