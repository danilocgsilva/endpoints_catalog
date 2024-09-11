<?php

declare(strict_types=1);

namespace Tests\Repositories;

use Danilocgsilva\EndpointsCatalog\Migrations\Migrations\FacadeComponents\PlatformsTable;
use Danilocgsilva\EndpointsCatalog\Repositories\PlatformRepository;
use Exception;
use PHPUnit\Framework\TestCase;
use Danilocgsilva\EndpointsCatalog\Migrations\NoMigrationsLeftException;
use Tests\Utils;
use Danilocgsilva\EndpointsCatalog\Models\Platform;
use Danilocgsilva\EndpointsCatalog\Repositories\Interfaces\BaseRepositoryInterface;
use Danilocgsilva\EndpointsCatalog\Migrations\Manager;

class PlatformRepositoryTest extends TestCase
{
    private Utils $dbUtils;

    /**
     * @var BaseRepositoryInterface<Platform> $repository
     */
    private PlatformRepository $platformRepository;

    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->dbUtils = new Utils();

        $migrationManager = new Manager($this->dbUtils->getDatabaseName(), $this->dbUtils->getPdo());
        if ($migrationManager->haveNextMigration()) {
            $migration = $migrationManager->getNextMigration();
            $this->dbUtils->migrate($migration);
        }
    }

    public function setUp(): void
    {
        $this->platformRepository = new PlatformRepository($this->dbUtils->getPdo());
    }

    public function testSimpleSave(): void
    {
        $platformName = "Business drive";
        $this->cleanTables();
        $this->dbUtils->migrate(new PlatformsTable());
        
        $this->assertSame(0, $this->dbUtils->getTableCount('platforms'));
        
        $platform = new Platform();
        $platform->setName($platformName);
        $this->platformRepository->save($platform);
        $this->assertSame(1, $this->dbUtils->getTableCount('platforms'));
    }

    public function testGet(): void
    {
        $this->cleanTables();

        $this->assertSame(0, $this->dbUtils->getTableCount('platforms'));
        $this->dbUtils->fillTable('platforms', [
            [
                "name" => "Business drive"
            ]
        ]);
        $this->assertSame(1, $this->dbUtils->getTableCount('platforms'));

        $retrievedPlatoform = $this->platformRepository->get(1);
        $this->assertSame("Business drive", $retrievedPlatoform->name);
    }

    public function testBothFieldsTest(): void
    {
        $this->cleanTables();
        $this->assertSame(0, $this->dbUtils->getTableCount('platforms'));
        $this->dbUtils->fillTable('platforms', [
            [
                "name" => "Business drive",
                "description" => "A platoform business related to driving",
            ]
        ]);

        $retrievedPlatoform = $this->platformRepository->get(1);
        $this->assertSame("Business drive", $retrievedPlatoform->name);
        $this->assertSame("A platoform business related to driving", $retrievedPlatoform->description);
    }

    public function testReplace(): void
    {
        $this->cleanTables();
        $this->dbUtils->fillTable('platforms', [
            ["name" => "Drivers platform"]
        ]);

        $toReplacePlatform = (new Platform())->setName("Buses platform");
        $this->platformRepository->replace(1, $toReplacePlatform);

        $recoveredAfterReplace = $this->platformRepository->get(1);

        $this->assertSame("Buses platform", $recoveredAfterReplace->name);
    }

    public function testDelete(): void
    {
        $this->cleanTables();

        $this->dbUtils->fillTable('platforms', [
            [
                "name" => "Drivers platform"
            ]
        ]);

        $this->assertSame(1, $this->dbUtils->getTableCount('platforms'));
        $this->platformRepository->delete(1);
        $this->assertSame(0, $this->dbUtils->getTableCount('platforms'));
    }

    public function testList(): void
    {
        $this->cleanTables();

        $this->dbUtils->fillTable('platforms', [
            ["name" => "Drivers platform"],
            ["name" => "Motorcycles platform"],
        ]);

        $this->assertSame(2, $this->dbUtils->getTableCount('platforms'));
        /** @var array<Platform> $listOfPlatforms */
        $listOfPlatforms = $this->platformRepository->list();
        $this->assertCount(2, $listOfPlatforms);

        $firstPlatform = $listOfPlatforms[0];
        $this->assertSame("Drivers platform", $firstPlatform->name);
    }

    private function cleanTables(): void
    {
        $this->dbUtils->cleanTable('platforms');
    }

    private function fill(): void
    {
        $this->dbUtils->fillTable('platform', [
            ["name" => "Business drive"]
        ]);
    }
}
