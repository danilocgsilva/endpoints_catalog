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
    private Utils $utils;

    private Manager $manager;
    
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->utils = new Utils();
        $this->manager = new Manager($this->utils->getDatabaseName(), $this->utils->getPdo());
    }
    
    public function testGetFirstMigration()
    {
        $this->utils->dropAllForeignKeysAndTables();
        $nextMigration = $this->manager->getNextMigration();
        $this->assertInstanceOf(M01_Apply::class, $nextMigration);
    }

    public function testGetSecondMigration(): void
    {
        $this->utils->dropAllForeignKeysAndTables();
        $firstMigration = $this->manager->getNextMigration();
        $this->utils->migrate($firstMigration);
        $secondMigration = $this->manager->getNextMigration();
        $this->assertInstanceOf(M02_PlatformsPayload::class, $secondMigration);
    }
}
