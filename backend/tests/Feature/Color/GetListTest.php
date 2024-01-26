<?php

namespace App\Tests\Feature\Color;

use App\Repository\ColorRepository;
use App\Tests\ApiTestCase;
use App\Tests\Traits\RefreshDatabaseTrait;

class GetListTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    /** @test */
    public function it_should_show_the_list_of_colors_to_user_as_expected(): void
    {
        $response = static::createClient()->request(
            'GET',
            '/api/v1/colors'
        );

        $this->assertJsonContains([
            '@context' => '/api/contexts/Color',
            '@id' => '/api/v1/colors',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => $this->getColorsCount(),
        ]);
        $item = $response->toArray()['hydra:member'][0];
        $this->assertStringContainsString('/api/v1/colors/', $item['@id']);
        $this->assertArrayHasKey('id', $item);
    }

    /** @test */
    public function it_should_response_ok(): void
    {
        static::createClient()->request('GET', '/api/v1/colors');

        $this->assertResponseIsSuccessful();
    }

    private function getColorsCount(): int
    {
        return $this->getContainer()
            ->get(ColorRepository::class)
            ->count([]);
    }
}
