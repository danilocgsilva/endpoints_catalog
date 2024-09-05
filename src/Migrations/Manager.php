<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Migrations;

use Danilocgsilva\EndpointsCatalog\Migrations\Migrations\{M01_Apply, M02_Platforms};
use Danilocgsilva\EndpointsCatalog\Models\{Path, Dns, DnsPath};
use PDO;

class Manager
{
    public function __construct(private string $databaseName, private PDO $pdo) {}
    
    public function getNextMigration(): MigrationInterface
    {
        if ($this->haveTablesFirstMigration()) {
            return new M02_Platforms();
        }
        throw new NoMigrationsLeftException();
    }

    public function getPreviousMigration(): MigrationInterface
    {
        return new M01_Apply();
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
        $query = sprintf("USE %s;", $this->databaseName);
        $query .= PHP_EOL . "SHOW TABLES;";
        $preResults = $this->pdo->prepare($query);
        $preResults->setFetchMode(PDO::FETCH_NUM);
        $preResults->execute();
        $tables = [];
        while ($row = $preResults->fetch()) {
            $tables[] = $row[0];
        }
        return $tables;
    }
}
