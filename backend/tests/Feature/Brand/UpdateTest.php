<?php

namespace App\Tests\Feature\Brand;

use App\Entity\Brand;
use App\Repository\BrandRepository;
use App\Tests\ApiTestCase;
use App\Tests\Traits\RefreshDatabaseTrait;

class UpdateTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    /** @test */
    public function user_can_update_a_brand(): void
    {
        $brand = $this->getBrand();
        $data = ['name' => 'lamborghini'];

        static::createClient()->request(
            'PUT',
            '/api/v1/brands/' . $brand->getId(),
            ['json' => $data]
        );

        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains([
            '@context' => '/api/contexts/Brand',
            '@id' => "/api/v1/brands/{$brand->getId()}",
            '@type' => 'Brand',
            'id' => $brand->getId(),
            'name' => $data['name'],
        ]);
    }

    public function getBrand(): Brand
    {
        return $this->getContainer()
            ->get(BrandRepository::class)
            ->findAll([])[5];
    }
}
