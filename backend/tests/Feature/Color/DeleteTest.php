<?php

namespace App\Tests\Feature\Color;

use App\Entity\Color;
use App\Repository\ColorRepository;
use App\Tests\ApiTestCase;
use App\Tests\Traits\RefreshDatabaseTrait;
use Doctrine\ORM\EntityManager;

class DeleteTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    /** @test */
    public function user_can_delete_a_color(): void
    {
        $color = new Color();
        $color->setName('deletable color');
        /** @var EntityManager $entityManager */
        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');
        $entityManager->persist($color);
        $entityManager->flush();

        static::createClient()->request(
            'DELETE',
            '/api/v1/colors/' . $color->getId()
        );

        $this->assertResponseStatusCodeSame(204);
    }

    public function getColor(): Color
    {
        return $this->getContainer()
            ->get(ColorRepository::class)
            ->findOneBy([]);
    }
}
