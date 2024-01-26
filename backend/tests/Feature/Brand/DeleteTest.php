<?php

namespace App\Tests\Feature\Brand;

use App\Entity\Brand;
use App\Repository\BrandRepository;
use App\Tests\ApiTestCase;
use App\Tests\Traits\RefreshDatabaseTrait;
use Doctrine\ORM\EntityManager;

class DeleteTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    /** @test */
    public function user_can_delete_a_brand(): void
    {
        $brand = new Brand();
        $brand->setName('deletable brand');
        /** @var EntityManager $entityManager */
        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');
        $entityManager->persist($brand);
        $entityManager->flush();

        static::createClient()->request(
            'DELETE',
            '/api/v1/brands/' . $brand->getId()
        );

        $this->assertResponseStatusCodeSame(204);
    }

    public function getBrand(): Brand
    {
        return $this->getContainer()
            ->get(BrandRepository::class)
            ->findOneBy([]);
    }
}
