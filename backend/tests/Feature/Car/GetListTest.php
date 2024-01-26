<?php

namespace App\Tests\Feature\Car;

use App\Repository\CarRepository;
use App\Tests\ApiTestCase;
use App\Tests\Traits\RefreshDatabaseTrait;

class GetListTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    /** @test */
    public function it_should_show_the_list_of_cars_to_user_as_expected(): void
    {
        $response = static::createClient()->request(
            'GET',
            '/api/v1/cars'
        );

        $this->assertJsonContains([
            '@context' => '/api/contexts/Car',
            '@id' => '/api/v1/cars',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => $this->getCarsCount(),
        ]);
        $item = $response->toArray()['hydra:member'][0];
        $this->assertStringContainsString('/api/v1/cars/', $item['@id']);
        $this->assertArrayHasKey('id', $item);
    }

    /** @test */
    public function it_should_response_ok(): void
    {
        static::createClient()->request('GET', '/api/v1/cars');

        $this->assertResponseIsSuccessful();
    }

    private function getCarsCount(): int
    {
        return $this->getContainer()
            ->get(CarRepository::class)
            ->count([]);
    }
}
