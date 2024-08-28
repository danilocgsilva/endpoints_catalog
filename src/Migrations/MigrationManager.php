<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Migrations;

use PDO;
use PDOException;
use Danilocgsilva\EndpointsCatalog\NoMigrationsLeftException;

class MigrationManager
{
    public function __construct(private PDO $pdo) {}
    
    public function getNextMigrationClass(): string
    {
        if (!$this->hasTable('dns_path')) {
            return "Danilocgsilva\EndpointsCatalog\Migrations\Apply\M01_Apply";    
        }
        if (
            $this->hasTable('dns_path') &&
            !$this->hasTable('migrations')
        ) {
            return "Danilocgsilva\EndpointsCatalog\Migrations\Apply\M02_MetaTable";
        }
        throw new NoMigrationsLeftException();
    }

    public function getPreviouseMigrationClass(): string
    {
        if ($this->hasTable('migrations')) {
            return "Danilocgsilva\EndpointsCatalog\Migrations\Rollback\M02_MetaTableRollback";
        }
        if ($this->hasTable('dns_path')) {
            return "Danilocgsilva\EndpointsCatalog\Migrations\Rollback\M01_Rollback";
        }
        throw new NoMigrationsLeftException();
    }

    private function hasTable(string $table)
    {
        try {
            $this->pdo->query("SELECT 1 FROM {$table} LIMIT 1");
        } catch (PDOException $e) {
            return false;
        }
    
        return true;
    }
}
