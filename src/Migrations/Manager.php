<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Migrations;

use Danilocgsilva\EndpointsCatalog\Migrations\Migrations\M01_Apply, Danilocgsilva\EndpointsCatalog\Migrations\Migrations\M02_PlatformsPayload;
use Danilocgsilva\EndpointsCatalog\Models\{Path, Dns, DnsPath};
use Danilocgsilva\EndpointsCatalog\Models\Platform;
use PDO;

class Manager
{
    /**
     * @var array<string>
     */
    private array $tables;
    
    private bool $isHaveNextMigration;
    
    public function __construct(private string $databaseName, private PDO $pdo) {}
    
    /**
     * @throws \Danilocgsilva\EndpointsCatalog\Migrations\NoMigrationsLeftException
     * @return \Danilocgsilva\EndpointsCatalog\Migrations\MigrationInterface
     */
    public function getNextMigration(): MigrationInterface
    {
        if ($this->haveNextMigration()) {
            if (!$this->haveTablesFirstMigration()) {
                return new M01_Apply();
            }
            return new M02_PlatformsPayload();
        }
        throw new NoMigrationsLeftException();
    }

    public function haveNextMigration(): bool
    {
        if (!isset($this->isHaveNextMigration)) {
            if ($this->haveTablesFirstMigration()) {
                if (!in_array(Platform::TABLENAME, $this->tables)) {
                    $this->isHaveNextMigration = true;
                } else {
                    $this->isHaveNextMigration = false;
                }
            } else {
                $this->isHaveNextMigration = true;
            }
        }
        return $this->isHaveNextMigration;
    }

    /**
     * @throws \Danilocgsilva\EndpointsCatalog\Migrations\NoMigrationsLeftException
     * @return \Danilocgsilva\EndpointsCatalog\Migrations\MigrationInterface
     */
    public function getPreviousMigration(): MigrationInterface
    {
        // Last migrations check must be checked first than the previous migrations check.
        if (in_array(Platform::TABLENAME, $this->tables)) {
            return new M02_PlatformsPayload();
        }

        if ($this->haveTablesFirstMigration()) {
            return new M01_Apply();
        }

        throw new NoMigrationsLeftException();
    }

    private function haveTablesFirstMigration(): bool
    {
        $this->updateTableList();
        return
            in_array(Path::TABLENAME, $this->tables) &&
            in_array(Dns::TABLENAME, $this->tables) &&
            in_array(DnsPath::TABLENAME, $this->tables);
    }

    /**
     * @return array<string>
     */
    private function updateTableList(): void
    {
        $queryUse = sprintf("USE %s;", $this->databaseName);
        $this->pdo->prepare($queryUse)->execute();

        $query = "SHOW TABLES;";
        $preResults = $this->pdo->prepare($query);
        $preResults->setFetchMode(PDO::FETCH_NUM);
        $preResults->execute();
        $this->tables = [];
        while ($row = $preResults->fetch()) {
            $this->tables[] = $row[0];
        }
    }
}
