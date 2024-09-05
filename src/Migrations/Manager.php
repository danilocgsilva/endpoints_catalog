<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Migrations;

use Danilocgsilva\EndpointsCatalog\Migrations\Migrations\{M01_Apply, M02_Platforms};
use Danilocgsilva\EndpointsCatalog\Models\{Path, Dns, DnsPath};
use Danilocgsilva\EndpointsCatalog\Models\Platform;
use PDO;

class Manager
{
    /**
     * @var array<string>
     */
    private array $tables;
    
    public function __construct(private string $databaseName, private PDO $pdo) {}
    
    /**
     * @throws \Danilocgsilva\EndpointsCatalog\Migrations\NoMigrationsLeftException
     * @return \Danilocgsilva\EndpointsCatalog\Migrations\MigrationInterface
     */
    public function getNextMigration(): MigrationInterface
    {
        if ($this->haveTablesFirstMigration()) {
            if (!in_array(Platform::TABLENAME, $this->tables)) {
                return new M02_Platforms();
            }
        }
        throw new NoMigrationsLeftException();
    }

    /**
     * @throws \Danilocgsilva\EndpointsCatalog\Migrations\NoMigrationsLeftException
     * @return \Danilocgsilva\EndpointsCatalog\Migrations\MigrationInterface
     */
    public function getPreviousMigration(): MigrationInterface
    {
        // Last migrations check must be checked first than the previous migrations check.
        if (in_array(Platform::TABLENAME, $this->listTables())) {
            return new M02_Platforms();
        }

        if ($this->haveTablesFirstMigration()) {
            return new M01_Apply();
        }

        throw new NoMigrationsLeftException();
    }

    private function haveTablesFirstMigration(): bool
    {
        /** @var array<string> */
        $tables = $this->listTables();
        return
            in_array(Path::TABLENAME, $tables) &&
            in_array(Dns::TABLENAME, $tables) &&
            in_array(DnsPath::TABLENAME, $tables);
    }

    /**
     * @return array<string>
     */
    private function listTables(): array
    {
        if (isset($this->tables)) {
            return $this->tables;
        }

        $query = sprintf("USE %s;", $this->databaseName);
        $this->pdo->prepare($query)->execute();

        $query = "SHOW TABLES;";
        $preResults = $this->pdo->prepare($query);
        $preResults->setFetchMode(PDO::FETCH_NUM);
        $preResults->execute();
        $this->tables = [];
        while ($row = $preResults->fetch()) {
            $this->tables[] = $row[0];
        }

        return $this->tables;
    }
}
