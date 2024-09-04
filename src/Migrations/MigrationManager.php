<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Migrations;

use PDO;
use PDOException;
use Danilocgsilva\EndpointsCatalog\NoMigrationsLeftException;

class MigrationManager
{
    public function __construct(private PDO $pdo) {}
    
    /**
     * @throws \Danilocgsilva\EndpointsCatalog\NoMigrationsLeftException
     * @return string
     */
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
        if (
            $this->hasTable('dns_path') &&
            $this->hasTable('migrations') &&
            $this->doesNotHaveDescriptionFieldInDnsTable()
        ) {
            return "Danilocgsilva\EndpointsCatalog\Migrations\Apply\M03_AddDescriptionDns";
        }
        throw new NoMigrationsLeftException();
    }

    /**
     * @throws \Danilocgsilva\EndpointsCatalog\NoMigrationsLeftException
     * @return string
     */
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

    private function hasTable(string $table): bool
    {
        try {
            $this->pdo->query("SELECT 1 FROM {$table} LIMIT 1");
        } catch (PDOException $e) {
            return false;
        }
    
        return true;
    }

    private function doesNotHaveDescriptionFieldInDnsTable(): bool
    {
        $databaseName = $this->pdo->query('SELECT database()')->fetchColumn();

        $stringQuery = "SELECT " . 
            "COLUMN_NAME, TABLE_SCHEMA, TABLE_NAME " .
            "FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = :databasename " . 
            "AND TABLE_NAME = :dns " . 
            "AND COLUMN_NAME = :column_name " .
            "ORDER BY ordinal_position;";
        $preResults = $this->pdo->prepare($stringQuery);
        $preResults->setFetchMode(PDO::FETCH_ASSOC);
        $preResults->execute([
            ':databasename' => $databaseName,
            ':dns' => 'dns',
        ]);

        $row = $preResults->fetch();
        return $row === null ? true : false;
    }
}
