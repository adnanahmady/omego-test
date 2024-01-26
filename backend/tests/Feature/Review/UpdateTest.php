<?php

namespace App\Tests\Feature\Review;

use App\Entity\Car;
use App\Entity\Review;
use App\Repository\CarRepository;
use App\Repository\ReviewRepository;
use App\Tests\ApiTestCase;
use App\Tests\Traits\RefreshDatabaseTrait;

class UpdateTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    /** @test */
    public function user_can_update_a_review(): void
    {
        $review = $this->getReview();
        $data = [
            'rate' => 3,
            'content' => 'this car is not that good',
            'car' => '/api/v1/cars/' . $this->getCar()->getId(),
        ];

        static::createClient()->request(
            'PUT',
            '/api/v1/reviews/' . $review->getId(),
            ['json' => $data]
        );

        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains([
            '@context' => '/api/contexts/Review',
            '@id' => "/api/v1/reviews/{$review->getId()}",
            '@type' => 'Review',
            'id' => $review->getId(),
            'rate' => $data['rate'],
            'content' => $data['content'],
        ]);
    }

    public function getReview(): Review
    {
        return $this->getContainer()
            ->get(ReviewRepository::class)
            ->findOneBy([]);
    }

    public function getCar(): Car
    {
        return $this->getContainer()
            ->get(CarRepository::class)
            ->findOneBy([]);
    }
}
