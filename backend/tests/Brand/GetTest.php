<?php

namespace App\Tests\Brand;

use App\DataFixtures\CarFixture;
use App\DataFixtures\ColorFixture;
use App\Entity\Brand;
use App\Repository\BrandRepository;
use App\Tests\ApiTestCase;
use App\Tests\Traits\RefreshDatabaseTrait;

class GetTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    /** @test */
    public function it_should_show_a_single_brand_using_the_given_brand_id(): void
    {
        $brand = $this->getBrand();
        $entityManger = $this->getContainer()->get('doctrine.orm.entity_manager');
        $color = (new ColorFixture())->createColor($entityManger);
        $car = (new CarFixture())->createCar($brand, $color, $entityManger);
        $brand->addCar($car);

        $response = static::createClient()->request(
            'GET',
            '/api/v1/brands/' . $brand->getId()
        );

        $this->assertJsonContains([
            '@context' => '/api/contexts/Brand',
            '@id' => "/api/v1/brands/{$brand->getId()}",
            '@type' => 'Brand',
            'id' => $brand->getId(),
            'name' => $brand->getName(),
        ]);
        $this->assertSame([
            '@id' => "/api/v1/cars/{$brand->getCars()[0]->getId()}",
            '@type' => 'Car',
            'id' => $brand->getCars()[0]->getId(),
            'model' => $brand->getCars()[0]->getModel(),
            'color' => "/api/v1/colors/{$brand->getCars()[0]->getColor()->getId()}",
        ], $response->toArray()['cars'][0]);
    }

    /** @test */
    public function it_should_response_ok(): void
    {
        static::createClient()->request(
            'GET',
            '/api/v1/brands/' . $this->getBrand()->getId()
        );

        $this->assertResponseIsSuccessful();
    }

    public function getBrand(): Brand
    {
        return $this->getContainer()
            ->get(BrandRepository::class)
            ->findOneBy([]);
    }
}
