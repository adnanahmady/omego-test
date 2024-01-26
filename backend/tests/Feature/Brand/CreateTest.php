<?php

namespace App\Tests\Feature\Brand;

use App\Tests\ApiTestCase;
use App\Tests\Traits\RefreshDatabaseTrait;

class CreateTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    /** @test */
    public function user_can_create_a_brand(): void
    {
        $data = ['name' => 'bmw'];

        $response = static::createClient()->request(
            'POST',
            '/api/v1/brands',
            ['json' => $data]
        );

        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains([
            '@context' => '/api/contexts/Brand',
            '@type' => 'Brand',
            'name' => $data['name'],
        ]);
        $data = $response->toArray();
        $this->assertStringContainsString('/api/v1/brands/', $data['@id']);
        $this->assertArrayHasKey('id', $data);
    }
}
