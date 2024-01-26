<?php

namespace App\DataFixtures;

use App\Entity\Color;
use Doctrine\Persistence\ObjectManager;

class ColorFixture extends BaseFixture
{
    public function load(ObjectManager $manager): void
    {
        $color = $this->createColor($manager);
        $this->addReference('color', $color);
    }

    public function createColor(ObjectManager $manager): Color
    {
        $color = new Color();
        $color->setName($this->faker->name());
        $manager->persist($color);
        $manager->flush();

        return $color;
    }
}
