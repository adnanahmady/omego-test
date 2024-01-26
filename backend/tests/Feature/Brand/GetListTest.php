<?php

namespace App\Tests\Feature\Brand;

use App\Repository\BrandRepository;
use App\Tests\ApiTestCase;
use App\Tests\Traits\RefreshDatabaseTrait;

class GetListTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    /** @test */
    public function it_should_show_the_list_of_brands_to_user_as_expected(): void
    {
        $response = static::createClient()->request(
            'GET',
            '/api/v1/brands'
        );

        $this->assertJsonContains([
            '@context' => '/api/contexts/Brand',
            '@id' => '/api/v1/brands',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => $this->getBrandsCount(),
        ]);
        $item = $response->toArray()['hydra:member'][0];
        $this->assertStringContainsString('/api/v1/brands/', $item['@id']);
        $this->assertArrayHasKey('id', $item);
    }

    /** @test */
    public function it_should_response_ok(): void
    {
        static::createClient()->request('GET', '/api/v1/brands');

        $this->assertResponseIsSuccessful();
    }

    private function getBrandsCount(): int
    {
        return $this->getContainer()
            ->get(BrandRepository::class)
            ->count([]);
    }
}
