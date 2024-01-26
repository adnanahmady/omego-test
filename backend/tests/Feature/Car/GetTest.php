<?php

namespace App\Tests\Feature\Car;

use App\Entity\Car;
use App\Repository\CarRepository;
use App\Tests\ApiTestCase;
use App\Tests\Traits\RefreshDatabaseTrait;

class GetTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    /** @test */
    public function it_should_show_a_single_car_using_the_given_car_id(): void
    {
        $car = $this->getCar();

        static::createClient()->request(
            'GET',
            '/api/v1/cars/' . $car->getId()
        );

        $this->assertJsonContains([
            '@context' => '/api/contexts/Car',
            '@id' => "/api/v1/cars/{$car->getId()}",
            '@type' => 'Car',
            'brand' => [
                '@id' => "/api/v1/brands/{$car->getBrand()->getId()}",
                '@type' => 'Brand',
                'id' => $car->getBrand()->getId(),
                'name' => $car->getBrand()->getName(),
            ],
            'id' => $car->getId(),
            'model' => $car->getModel(),
            'color' => [
                '@id' => "/api/v1/colors/{$car->getColor()->getId()}",
                '@type' => 'Color',
                'id' => $car->getColor()->getId(),
                'name' => $car->getColor()->getName(),
            ],
        ]);
    }

    /** @test */
    public function it_should_response_ok(): void
    {
        static::createClient()->request(
            'GET',
            '/api/v1/cars/' . $this->getCar()->getId()
        );

        $this->assertResponseIsSuccessful();
    }

    public function getCar(): Car
    {
        return $this->getContainer()
            ->get(CarRepository::class)
            ->findOneBy([]);
    }
}
