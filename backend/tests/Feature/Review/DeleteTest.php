<?php

namespace App\Tests\Feature\Review;

use App\Entity\Review;
use App\Repository\ReviewRepository;
use App\Tests\ApiTestCase;
use App\Tests\Traits\RefreshDatabaseTrait;

class DeleteTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    /** @test */
    public function user_can_delete_a_review(): void
    {
        static::createClient()->request(
            'DELETE',
            '/api/v1/reviews/' . $this->getReview()->getId()
        );

        $this->assertResponseStatusCodeSame(204);
    }

    public function getReview(): Review
    {
        return $this->getContainer()
            ->get(ReviewRepository::class)
            ->findOneBy([]);
    }
}
