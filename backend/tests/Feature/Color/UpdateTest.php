<?php

namespace App\Tests\Feature\Color;

use App\Entity\Color;
use App\Repository\ColorRepository;
use App\Tests\ApiTestCase;
use App\Tests\Traits\RefreshDatabaseTrait;

class UpdateTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    /** @test */
    public function user_can_update_a_color(): void
    {
        $color = $this->getColor();
        $data = ['name' => 'green'];

        static::createClient()->request(
            'PUT',
            '/api/v1/colors/' . $color->getId(),
            ['json' => $data]
        );

        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains([
            '@context' => '/api/contexts/Color',
            '@id' => "/api/v1/colors/{$color->getId()}",
            '@type' => 'Color',
            'id' => $color->getId(),
            'name' => $data['name'],
        ]);
    }

    public function getColor(): Color
    {
        return $this->getContainer()
            ->get(ColorRepository::class)
            ->findAll([])[5];
    }
}
