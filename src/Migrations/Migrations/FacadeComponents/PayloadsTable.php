<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Migrations\Migrations\FacadeComponents;

use Danilocgsilva\ClassToSqlSchemaScript\ForeignKeyScriptSpitter;
use Danilocgsilva\EndpointsCatalog\Migrations\MigrationInterface;
use Danilocgsilva\EndpointsCatalog\Migrations\NoMigrationsLeftException;
use Danilocgsilva\EndpointsCatalog\Models\Payload;
use Danilocgsilva\EndpointsCatalog\Models\Path;
use Danilocgsilva\ClassToSqlSchemaScript\TableScriptSpitter;
use Danilocgsilva\ClassToSqlSchemaScript\FieldScriptSpitter;

class PayloadsTable implements MigrationInterface
{
    private array $tables;

    public function __construct()
    {
        $this->tables = [Payload::TABLENAME];
    }

    public function getTablesNames(): array
    {
        return $this->tables;
    }

    public function getString(): string
    {
        return $this->getPayloadsCreationTableScript();
    }

    public function getRollbackString(): string
    {

        $querySafeGuard = <<<EOF
        SET @constraint_name = 'path_id_path_constraint';
        SET @check_constraint = (SELECT COUNT(*)
                                 FROM information_schema.table_constraints
                                 WHERE table_schema = DATABASE()
                                   AND table_name = 'payloads'
                                   AND constraint_name = @constraint_name);

        SET @drop_table = DROP TABLE 'payloads';
        
        SET @sql = IF(@check_constraint > 0,
                      CONCAT('ALTER TABLE payloads DROP FOREIGN KEY ', @constraint_name, @drop_table),
                      'SELECT 1');
        
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
EOF;

        print($querySafeGuard);

        return $querySafeGuard;
    }

    private function getPayloadsCreationTableScript(): string
    {
        return (new TableScriptSpitter(Payload::TABLENAME))
            ->addField(
                (new FieldScriptSpitter('id'))
                    ->setType("INT")
                    ->setPrimaryKey()
                    ->setNotNull()
                    ->setUnsigned()
            )
            ->addField(
                (new FieldScriptSpitter('payload'))
                    ->setType("TEXT")
                    ->setNotNull()
            )
            ->addField(
                (new FieldScriptSpitter('name'))
                    ->setType("VARCHAR(192)")
                    ->setNotNull()
            )
            ->addField(
                (new FieldScriptSpitter('path_id'))
                    ->setType("INT")
                    ->setNotNull()
                    ->setUnsigned()
            )
            ->getScript();
    }

    private function getForeignKeyToPathScript(): string
    {
        return (new ForeignKeyScriptSpitter())
            ->setConstraintName("path_id_path_constraint")
            ->setForeignKey('path_id')
            ->setTable(Payload::TABLENAME)
            ->setForeignTable(Path::TABLENAME)
            ->getScript();
    }

    // private function payloadTableExists()
    // {

    // }
}

