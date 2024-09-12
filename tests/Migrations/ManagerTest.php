<?php

declare(strict_types=1);

namespace Tests\Migrations;

use PHPUnit\Framework\TestCase;
use Tests\Utils;
use Danilocgsilva\EndpointsCatalog\Migrations\Migrations\M01_Apply;
use Danilocgsilva\EndpointsCatalog\Migrations\Migrations\M02_PlatformsPayload;
use Danilocgsilva\EndpointsCatalog\Migrations\Manager;

class ManagerTest extends TestCase
{
    public function testGetFirstMigration()
    {
        $utils = new Utils();
        $utils->dropAllTables();
        $manager = new Manager($utils->getDatabaseName(), $utils->getPdo());
        $nextMigration = $manager->getNextMigration();
        $this->assertInstanceOf(M01_Apply::class, $nextMigration);
    }
}
