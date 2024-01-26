<?php

namespace App\Tests\Car;

use App\Entity\Brand;
use App\Entity\Color;
use App\Repository\BrandRepository;
use App\Repository\ColorRepository;
use App\Tests\ApiTestCase;
use App\Tests\Traits\RefreshDatabaseTrait;

class CreateTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    /** @test */
    public function user_can_create_a_car(): void
    {
        $brand = $this->getBrand();
        $color = $this->getColor();
        $data = [
            'brand' => '/api/v1/brands/' . $brand->getId(),
            'model' => 'xdd',
            'color' => '/api/v1/colors/' . $color->getId(),
        ];

        $response = static::createClient()->request(
            'POST',
            '/api/v1/cars',
            ['json' => $data]
        );

        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains([
            '@context' => '/api/contexts/Car',
            '@type' => 'Car',
            'brand' => [
                '@id' => "/api/v1/brands/{$brand->getId()}",
                '@type' => 'Brand',
                'id' => $brand->getId(),
                'name' => $brand->getName(),
            ],
            'model' => 'xdd',
            'color' =>  [
                '@id' => "/api/v1/colors/{$color->getId()}",
                '@type' => 'Color',
                'id' => $color->getId(),
                'name' => $color->getName(),
            ],
        ]);
        $data = $response->toArray();
        $this->assertStringContainsString('/api/v1/cars/', $data['@id']);
        $this->assertArrayHasKey('id', $data);
    }

    public function getBrand(): Brand
    {
        return $this->getContainer()
            ->get(BrandRepository::class)
            ->findOneBy([]);
    }

    public function getColor(): Color
    {
        return $this->getContainer()
            ->get(ColorRepository::class)
            ->findOneBy([]);
    }
}
