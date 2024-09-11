<?php

declare(strict_types=1);

namespace Tests\Migrations;

use PHPUnit\Framework\TestCase;
use Tests\Utils;
use Danilocgsilva\EndpointsCatalog\Migrations\Migrations\M01_Apply;
use Danilocgsilva\EndpointsCatalog\Migrations\Migrations\M02_PlatformsPayload;

class ManagerTest extends TestCase
{
    public function testGetFirstMigration()
    {
        $utils = new Utils();
        $utils->dropAllTables();
        $this->assertTrue(true);
    }
}
