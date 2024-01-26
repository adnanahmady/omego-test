<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase as BaseApiTestCase;
use App\Tests\Traits\RefreshDatabaseTrait;

class ApiTestCase extends BaseApiTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $ref = new \ReflectionClass($this);
        $traits = $ref->getTraits();

        if (isset($traits[RefreshDatabaseTrait::class])) {
            $this->setUpRefreshDatabaseTrait();
        }
    }
}
