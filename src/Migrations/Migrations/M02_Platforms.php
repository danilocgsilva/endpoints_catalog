<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Migrations\Migrations;

use Danilocgsilva\ClassToSqlSchemaScript\AlterTableScriptSpitter;
use Danilocgsilva\ClassToSqlSchemaScript\FieldScriptSpitter;
use Danilocgsilva\ClassToSqlSchemaScript\ForeignKeyScriptSpitter;
use Danilocgsilva\ClassToSqlSchemaScript\TableScriptSpitter;
use Danilocgsilva\EndpointsCatalog\Migrations\MigrationInterface;
use Danilocgsilva\EndpointsCatalog\Models\Dns;
use Danilocgsilva\EndpointsCatalog\Models\Platform;

class M02_Platforms implements MigrationInterface
{
    public function getString(): string
    {
        $newPlatformTableString = $this->getNewTableString();
        $alterDnsString = $this->alterDnsTable();
        $foreignKey = $this->addForeignKeyToDns();

        return $newPlatformTableString . PHP_EOL . $alterDnsString . PHP_EOL . $foreignKey;
    }

    public function getRollbackString(): string
    {
        $removeForeignKeyScript = sprintf("ALTER TABLE %s DROP FOREIGN KEY %s", Dns::TABLENAME, 'dns_platform_id_constraint');
        $removeNewColumn = sprintf("ALTER TABLE %s DROP %s;", Dns::TABLENAME, 'platform_id');
        $removeTableScript = sprintf("DROP TABLE %s;", Platform::TABLENAME);

        return $removeForeignKeyScript . PHP_EOL . $removeNewColumn . PHP_EOL . $removeTableScript;
    }

    private function getNewTableString(): string
    {
        return (new TableScriptSpitter(Platform::TABLENAME))
            ->addField(
                (new FieldScriptSpitter('id'))
                    ->setPrimaryKey()
                    ->setType("INT")
                    ->setNotNull()
                    ->setUnsigned()
                    ->setAutoIncrement()
            )
            ->addField(
                (new FieldScriptSpitter('name'))
                    ->setType("VARCHAR(192)")
                    ->setNotNull()
            )
            ->addField(
                (new FieldScriptSpitter('description'))
                    ->setType("VARCHAR(192)")
            )
            ->getScript();
    }

    private function alterDnsTable(): string
    {
        return (new AlterTableScriptSpitter(Dns::TABLENAME))
            ->setNewColumn("platform_id")
            ->setType("INT")
            ->setNotNull()
            ->setUnsigned()
            ->getScript();
    }

    private function addForeignKeyToDns(): string
    {
        return (new ForeignKeyScriptSpitter())
            ->setConstraintName('dns_platform_id_constraint')
            ->setForeignKey('platform_id')
            ->setTable(Dns::TABLENAME)
            ->setForeignTable(Platform::TABLENAME)
            ->setTableForeignkey('id')
            ->getScript();
    }
}
