<?php

namespace App\Tests\Feature\Car;

use App\DataFixtures\BrandFixture;
use App\DataFixtures\CarFixture;
use App\DataFixtures\ColorFixture;
use App\DataFixtures\ReviewFixture;
use App\Entity\Car;
use App\Repository\CarRepository;
use App\Repository\ReviewRepository;
use App\Tests\ApiTestCase;
use App\Tests\Traits\RefreshDatabaseTrait;

class GetReviewListTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    /** @test */
    public function user_should_be_able_to_get_top_five_reviews_rated_more_than_six(): void
    {
        $manager = $this->getContainer()->get('doctrine.orm.entity_manager');
        $brand = (new BrandFixture())->createBrand($manager);
        $color = (new ColorFixture())->createColor($manager);
        $car = (new CarFixture())->createCar($brand, $color, $manager);

        array_map(
            fn () => (new ReviewFixture())->createReview($manager, $car, 1, 5),
            range(0, 10)
        );
        array_map(
            fn () => (new ReviewFixture())->createReview($manager, $car, 6, 8),
            range(0, 6)
        );
        $highest = (new ReviewFixture())->createReview($manager, $car, 9, 10);

        $response = static::createClient()->request(
            'GET',
            '/api/v1/cars/' . $car->getId() . '/reviews',
            ['query' => [
                'max-count' => $maxCount = 5,
                'rate-higher-than' => 6,
            ]]
        );

        $this->assertJsonContains([
            '@context' => '/api/contexts/Review',
            '@id' => '/api/v1/cars/' . $car->getId() . '/reviews',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => $maxCount,
        ]);
        $item = $response->toArray()['hydra:member'][0];
        $this->assertArrayHasKey('id', $item);
        $this->assertSame([
            '@id' => '/api/v1/reviews/' . $highest->getId(),
            '@type' => 'Review',
            'id' => $highest->getId(),
            'rate' => $highest->getRate(),
            'content' => $highest->getContent(),
            'car' => '/api/v1/cars/' . $car->getId(),
        ], $item);
    }

    /** @test */
    public function it_should_show_the_list_of_cars_to_user_as_expected(): void
    {
        $manager = $this->getContainer()->get('doctrine.orm.entity_manager');
        $car = $this->getCar();

        $reviews = array_map(
            fn () => (new ReviewFixture())->createReview($manager, $car),
            range(0, 10)
        );

        $response = static::createClient()->request(
            'GET',
            '/api/v1/cars/' . $car->getId() . '/reviews'
        );

        $this->assertJsonContains([
            '@context' => '/api/contexts/Review',
            '@id' => '/api/v1/cars/' . $car->getId() . '/reviews',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => $this->getCarReviewsCount($car),
        ]);
        $item = $response->toArray()['hydra:member'][0];
        $this->assertArrayHasKey('id', $item);
        $this->assertArrayHasKey('@id', $item);
        $this->assertArrayHasKey('@type', $item);
        $this->assertArrayHasKey('id', $item);
        $this->assertArrayHasKey('rate', $item);
        $this->assertArrayHasKey('content', $item);
        $this->assertSame('/api/v1/cars/' . $car->getId(), $item['car']);
    }

    /** @test */
    public function it_should_response_ok(): void
    {
        static::createClient()->request(
            'GET',
            '/api/v1/cars/' . $this->getCar()->getId() . '/reviews'
        );

        $this->assertResponseIsSuccessful();
    }

    private function getCarReviewsCount(Car $car): int
    {
        return $this->getContainer()
            ->get(ReviewRepository::class)
            ->count(['car' => $car->getId()]);
    }

    private function getCar(): Car
    {
        return $this->getContainer()
            ->get(CarRepository::class)
            ->findOneBy([]);
    }
}
