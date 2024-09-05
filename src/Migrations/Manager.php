<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Migrations;

use Danilocgsilva\EndpointsCatalog\Migrations\Migrations\M02_Platforms;
use Danilocgsilva\EndpointsCatalog\Models\{Path, Dns, DnsPath};
use PDO;

class Manager
{
    public function __construct(private string $databaseName, private PDO $pdo) {}
    
    private function getNextMigration(): string
    {
        if ($this->haveTablesFirstMigration()) {
            return M02_Platforms::class;
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
