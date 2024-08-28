<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Migrations;

use PDO;
use PDOException;

class MigrationManager
{
    public function getNextMigrationClass(): string
    {
        return "Danilocgsilva\EndpointsCatalog\Migrations\Apply\M02_MetaTable";
    }

    public function getPreviouseMigrationClass(PDO $pdo): string
    {
        if ($this->hasTable('migrations', $pdo)) {
            return "Danilocgsilva\EndpointsCatalog\Migrations\Rollback\M02_MetaTableRollback";
        }
        return "Danilocgsilva\EndpointsCatalog\Migrations\Rollback\M01_Rollback";
    }

    private function hasTable(string $table, PDO $pdo)
    {
        try {
            $pdo->query("SELECT 1 FROM {$table} LIMIT 1");
        } catch (PDOException $e) {
            return false;
        }
    
        return true;
    }
}
