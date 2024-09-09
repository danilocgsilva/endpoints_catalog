<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Migrations\Migrations\FacadeComponents;

use Danilocgsilva\ClassToSqlSchemaScript\ForeignKeyScriptSpitter;
use Danilocgsilva\EndpointsCatalog\Migrations\MigrationInterface;
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
        $dropForeignKey = sprintf("ALTER TABLE %s DROP FOREIGN KEY %s;", Payload::TABLENAME, "path_id_path_constraint");
        
        $dropTableScript = sprintf("DROP TABLE %s;", Payload::TABLENAME);

        return $dropForeignKey . PHP_EOL . $dropTableScript;
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
}
