<?php

declare(strict_types=1);

namespace Tests;

use Danilocgsilva\EndpointsCatalog\Migrations\Rollback;
use Danilocgsilva\EndpointsCatalog\Migrations\Apply;
use Danilocgsilva\EndpointsCatalog\Repositories\DnsRepository;
use Danilocgsilva\EndpointsCatalog\Repositories\PathRepository;
use PDO;

class Utils
{
    private PDO $pdo;
    
    public function __construct()
    {
        $this->pdo = new PDO(
            sprintf(
                "mysql:host=%s;dbname=%s", getenv('DB_ENDPOINTSCATALOG_HOST_TEST'), getenv('DB_ENDPOINTSCATALOG_NAME_TEST')
            ),
            getenv('DB_ENDPOINTSCATALOG_USER_TEST'),
            getenv('DB_ENDPOINTSCATALOG_PASSWORD_TEST')
        );
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

    public function migrate(): void
    {
        $migrations = new Apply();
        
        $this->pdo->prepare(
            $migrations->getString()
        )->execute();
    }

    public function migrateRollback(): void
    {
        $migrations = new Rollback();

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
