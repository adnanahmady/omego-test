<?php

namespace App\Tests\Feature\Car;

use App\Entity\Brand;
use App\Entity\Car;
use App\Entity\Color;
use App\Repository\BrandRepository;
use App\Repository\CarRepository;
use App\Repository\ColorRepository;
use App\Tests\ApiTestCase;
use App\Tests\Traits\RefreshDatabaseTrait;

class UpdateTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    /** @test */
    public function user_can_update_a_car(): void
    {
        $car = $this->getCar();
        $brand = $this->getBrand();
        $color = $this->getColor();
        $data = [
            'brand' => '/api/v1/brands/' . $brand->getId(),
            'model' => 'xdd',
            'color' => '/api/v1/colors/' . $color->getId(),
        ];

        static::createClient()->request(
            'PUT',
            '/api/v1/cars/' . $car->getId(),
            ['json' => $data]
        );

        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains([
            '@context' => '/api/contexts/Car',
            '@id' => "/api/v1/cars/{$car->getId()}",
            '@type' => 'Car',
            'brand' => [
                '@id' => "/api/v1/brands/{$brand->getId()}",
                '@type' => 'Brand',
                'id' => $brand->getId(),
                'name' => $brand->getName(),
            ],
            'id' => $car->getId(),
            'model' => 'xdd',
            'color' => [
                '@id' => "/api/v1/colors/{$color->getId()}",
                '@type' => 'Color',
                'id' => $color->getId(),
                'name' => $color->getName(),
            ],
        ]);
    }

    public function getBrand(): Brand
    {
        return $this->getContainer()
            ->get(BrandRepository::class)
            ->findAll([])[5];
    }

    public function getColor(): Color
    {
        return $this->getContainer()
            ->get(ColorRepository::class)
            ->findAll([])[3];
    }

    public function getCar(): Car
    {
        return $this->getContainer()
            ->get(CarRepository::class)
            ->findOneBy([]);
    }
}
