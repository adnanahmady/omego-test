<?php

namespace App\Tests\Traits;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

trait RefreshDatabaseTrait
{
    protected static bool $isDataLoaded = false;

    protected function setUpRefreshDatabaseTrait(): void
    {
        $this->beforeRefreshingDatabase();

        if (!static::$isDataLoaded) {
            $this->findCommand('doctrine:database:create')->execute([]);
            $this->findCommand('doctrine:migrations:migrate')->execute([]);
            $this->findCommand('doctrine:fixture:load')->execute(
                ['--purge-with-truncate' => true],
                ['interactive' => false]
            );
            static::$isDataLoaded = true;
        }
    }

    private function findCommand(string $command): CommandTester
    {
        return new CommandTester($this->getApplication()->find($command));
    }

    private function getApplication(): Application
    {
        return new Application($this->bootKernel());
    }

    /**
     * You can refresh the database data
     * if refreshing data before the test
     * is necessary.
     */
    protected function beforeRefreshingDatabase(): void
    {
    }
}
