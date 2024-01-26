<?php

namespace App\Tests\Feature\Color;

use App\DataFixtures\BrandFixture;
use App\DataFixtures\CarFixture;
use App\Entity\Color;
use App\Repository\ColorRepository;
use App\Tests\ApiTestCase;
use App\Tests\Traits\RefreshDatabaseTrait;

class GetTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    /** @test */
    public function it_should_show_a_single_brand_using_the_given_color_id(): void
    {
        $color = $this->getColor();
        $entityManger = $this->getContainer()->get('doctrine.orm.entity_manager');
        $brand = (new BrandFixture())->createBrand($entityManger);
        $car = (new CarFixture())->createCar($brand, $color, $entityManger);
        $color->addCar($car);

        $response = static::createClient()->request(
            'GET',
            '/api/v1/colors/' . $color->getId()
        );

        $this->assertJsonContains([
            '@context' => '/api/contexts/Color',
            '@id' => "/api/v1/colors/{$color->getId()}",
            '@type' => 'Color',
            'id' => $color->getId(),
            'name' => $color->getName(),
        ]);
        $car = $color->getCars()[0];
        $this->assertSame([
            '@id' => "/api/v1/cars/{$car->getId()}",
            '@type' => 'Car',
            'id' => $car->getId(),
            'brand' => "/api/v1/brands/{$car->getBrand()->getId()}",
            'model' => $car->getModel(),
        ], $response->toArray()['cars'][0]);
    }

    /** @test */
    public function it_should_response_ok(): void
    {
        static::createClient()->request(
            'GET',
            '/api/v1/colors/' . $this->getColor()->getId()
        );

        $this->assertResponseIsSuccessful();
    }

    public function getColor(): Color
    {
        return $this->getContainer()
            ->get(ColorRepository::class)
            ->findOneBy([]);
    }
}
