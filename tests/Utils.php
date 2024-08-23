<?php

declare(strict_types=1);

namespace Tests;

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
        $query = sprintf("DELETE FROM %s;", $tableName);
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
}
