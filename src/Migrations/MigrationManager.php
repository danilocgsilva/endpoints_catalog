<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Migrations;

use PDO;
use PDOException;

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
        return "";
    }

    public function getPreviouseMigrationClass(PDO $pdo): string
    {
        if ($this->hasTable('migrations')) {
            return "Danilocgsilva\EndpointsCatalog\Migrations\Rollback\M02_MetaTableRollback";
        }
        return "Danilocgsilva\EndpointsCatalog\Migrations\Rollback\M01_Rollback";
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
