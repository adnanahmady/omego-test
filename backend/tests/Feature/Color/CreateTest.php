<?php

namespace App\Tests\Feature\Color;

use App\Tests\ApiTestCase;
use App\Tests\Traits\RefreshDatabaseTrait;

class CreateTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    /** @test */
    public function user_can_create_a_color(): void
    {
        $data = ['name' => 'blue'];

        $response = static::createClient()->request(
            'POST',
            '/api/v1/colors',
            ['json' => $data]
        );

        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains([
            '@context' => '/api/contexts/Color',
            '@type' => 'Color',
            'name' => $data['name'],
        ]);
        $data = $response->toArray();
        $this->assertStringContainsString('/api/v1/colors/', $data['@id']);
        $this->assertArrayHasKey('id', $data);
    }
}
