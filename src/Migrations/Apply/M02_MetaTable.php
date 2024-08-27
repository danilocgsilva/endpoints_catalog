<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Migrations\Apply;

use Danilocgsilva\EndpointsCatalog\Migrations\MigrationInterface;
use Danilocgsilva\ClassToSqlSchemaScript\TableScriptSpitter;
use Danilocgsilva\ClassToSqlSchemaScript\FieldScriptSpitter;

class M02_MetaTable implements MigrationInterface
{
    public function getString(): string
    {
        $tableSpitter = new TableScriptSpitter("migrations");

        $tableSpitter->addField(
            (new FieldScriptSpitter("id"))
            ->setType("INT")
            ->setNotNull()
            ->setPrimaryKey()
            ->setUnsigned()
            ->setAutoIncrement()
        );

        $tableSpitter->addField(
            (new FieldScriptSpitter("class"))
            ->setType("VARCHAR(255)")
        );

        return $tableSpitter->getScript();
    }
}
