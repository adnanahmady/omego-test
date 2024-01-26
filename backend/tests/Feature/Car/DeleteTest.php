<?php

namespace App\Tests\Feature\Car;

use App\Entity\Car;
use App\Repository\CarRepository;
use App\Tests\ApiTestCase;
use App\Tests\Traits\RefreshDatabaseTrait;

class DeleteTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    /** @test */
    public function user_can_delete_a_car(): void
    {
        $car = $this->getCar();

        static::createClient()->request(
            'DELETE',
            '/api/v1/cars/' . $car->getId()
        );

        $this->assertResponseStatusCodeSame(204);
    }

    public function getCar(): Car
    {
        return $this->getContainer()
            ->get(CarRepository::class)
            ->findOneBy([]);
    }
}
