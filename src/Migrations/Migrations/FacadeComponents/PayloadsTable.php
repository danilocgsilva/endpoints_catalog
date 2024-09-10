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
    public function getString(): string
    {
        return $this->getPayloadsCreationTableScript();
    }

    public function getRollbackString(): string
    {
        // $queryForeign = sprintf("IF EXISTS(SELECT table_name FROM INFORMATION_SCHEMA.TABLES WHERE table_schema = (SELECT DATABASE()) AND table_name = '%s') THEN " . PHP_EOL, Payload::TABLENAME);
        // $queryForeign .= sprintf("ALTER TABLE %s DROP FOREIGN KEY '%s';", Payload::TABLENAME, 'path_id_path_constraint') . PHP_EOL;
        // $queryForeign .= sprintf("DROP TABLE '%s';", Payload::TABLENAME) . PHP_EOL;
        // $queryForeign .= "END IF;" . PHP_EOL;

        // $queryForeign = 'SELECT CASE ' . PHP_EOL;
        // $queryForeign .= 'WHEN EXISTS (' . PHP_EOL;
        // $queryForeign .= 'SELECT 1 ' . PHP_EOL;
        // $queryForeign .= 'FROM INFORMATION_SCHEMA.TABLES ' . PHP_EOL;
        // $queryForeign .= 'WHERE table_schema = (SELECT DATABASE()) ' . PHP_EOL;
        // $queryForeign .= sprintf('AND TABLE_NAME = \'%s\'' . PHP_EOL, Payload::TABLENAME);
        // $queryForeign .= ')' . PHP_EOL;
        // $queryForeign .= 'THEN ' . PHP_EOL;
        // $queryForeign .= sprintf("ALTER TABLE %s DROP FOREIGN KEY '%s';", Payload::TABLENAME, 'path_id_path_constraint') . PHP_EOL;
        // $queryForeign .= sprintf("DROP TABLE '%s';", Payload::TABLENAME) . PHP_EOL;
        // $queryForeign .= 'END AS result;';

        $querySafeGuard = <<<EOF
        SET @constraint_name = 'path_id_path_constraint';
        SET @check_constraint = (SELECT COUNT(*)
                                 FROM information_schema.table_constraints
                                 WHERE table_schema = DATABASE()
                                   AND table_name = 'payloads'
                                   AND constraint_name = @constraint_name);

        SET @drop_table = DROP TABLE 'payloads';
        
        SET @sql = IF(@check_constraint > 0,
                      CONCAT('ALTER TABLE payloads DROP FOREIGN KEY ', @constraint_name, drop_table),
                      'SELECT 1');
        
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
EOF;

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

