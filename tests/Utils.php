<?php

declare(strict_types=1);

namespace Tests;

use Danilocgsilva\EndpointsCatalog\Repositories\DnsRepository;
use Danilocgsilva\EndpointsCatalog\Repositories\PathRepository;
use Danilocgsilva\EndpointsCatalog\Migrations\MigrationInterface;
use PDO;

class Utils
{
    private PDO $pdo;

    private string $databaseName;
    
    public function __construct()
    {
        $this->databaseName = getenv('DB_ENDPOINTSCATALOG_NAME_TEST');
        
        $this->pdo = new PDO(
            sprintf(
                "mysql:host=%s;dbname=%s", getenv('DB_ENDPOINTSCATALOG_HOST_TEST'), $this->databaseName
            ),
            getenv('DB_ENDPOINTSCATALOG_USER_TEST'),
            getenv('DB_ENDPOINTSCATALOG_PASSWORD_TEST')
        );
    }

    public function getDatabaseName(): string
    {
        return $this->databaseName;
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    public function getTableCount(string $tableName): int
    {
        $query = sprintf("SELECT COUNT(*) FROM %s;", $tableName);
        $preResults = $this->pdo->prepare($query);
        $preResults->execute();
        $preResults->setFetchMode(PDO::FETCH_NUM);
        return (int) $preResults->fetch()[0];
    }

    public function cleanTable(string $tableName): void
    {
        $query = sprintf("DELETE FROM %s; ALTER TABLE %s AUTO_INCREMENT = 1;", $tableName, $tableName);
        $this->pdo->prepare($query)->execute();
    }

    public function fillTable(string $tableName, array $data): void
    {
        foreach ($data as $entriesRecords) {
            $fields = array_keys($entriesRecords);
            $placeholders = array_map(fn($field) => ":$field", $fields);
    
            $query = sprintf(
                "INSERT INTO %s (%s) VALUES (%s);",
                $tableName,
                implode(", ", $fields),
                implode(", ", $placeholders)
            );
    
            $statement = $this->pdo->prepare($query);
            $statement->execute(array_combine($placeholders, $entriesRecords));
        }
    }

    public function migrate(MigrationInterface $migrations): void
    {
        $this->pdo->prepare(
            $migrations->getString()
        )->execute();
    }

    public function migrateRollback(MigrationInterface $migrations): void
    {
        $this->pdo->prepare(
            $migrations->getString()
        )->execute();
    }

    public function getPathRepository(): PathRepository
    {
        return new PathRepository($this->pdo);
    }

    public function getDnsRepository(): DnsRepository
    {
        return new DnsRepository($this->pdo);
    }
}
