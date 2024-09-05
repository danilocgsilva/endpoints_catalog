<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Migrations\Migrations;

use Danilocgsilva\ClassToSqlSchemaScript\FieldScriptSpitter;
use Danilocgsilva\ClassToSqlSchemaScript\TableScriptSpitter;
use Danilocgsilva\EndpointsCatalog\Migrations\MigrationInterface;
use Danilocgsilva\EndpointsCatalog\Models\Platform;

class M02_Platforms implements MigrationInterface
{
    public function getString(): string
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

    public function getRollbackString(): string
    {
        return sprintf("DROP TABLE %s;", Platform::TABLENAME);
    }
}
