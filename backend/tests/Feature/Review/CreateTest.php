<?php

namespace App\Tests\Feature\Review;

use App\Entity\Car;
use App\Repository\CarRepository;
use App\Tests\ApiTestCase;
use App\Tests\Traits\RefreshDatabaseTrait;

class CreateTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    /** @test */
    public function user_can_create_a_review(): void
    {
        $data = [
            'rate' => 1,
            'content' => 'this car is not that good',
            'car' => '/api/v1/cars/' . $this->getCar()->getId(),
        ];

        $response = static::createClient()->request(
            'POST',
            '/api/v1/reviews',
            ['json' => $data]
        );

        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains([
            '@context' => '/api/contexts/Review',
            '@type' => 'Review',
            'rate' => $data['rate'],
            'content' => $data['content'],
            'car' => $data['car'],
        ]);
        $data = $response->toArray();
        $this->assertStringContainsString('/api/v1/reviews/', $data['@id']);
        $this->assertArrayHasKey('id', $data);
    }

    public function getCar(): Car
    {
        return $this->getContainer()
            ->get(CarRepository::class)
            ->findOneBy([]);
    }
}
