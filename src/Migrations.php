<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Migrations;
use Danilocgsilva\ClassToSqlSchemaScript\TableScriptSpitter;
use Danilocgsilva\ClassToSqlSchemaScript\FieldScriptSpitter;

class Migrations
{
    const TABLE_NAME = "paths";
    
    public function getOnSql(): string
    {
        $onScript = "";

        $pathsTable = new TableScriptSpitter(self::TABLE_NAME);
        
        $pathsTable->addField(
            (new FieldScriptSpitter("id"))
            ->setType("INT")
            ->setNotNull()
            ->setPrimaryKey()
            ->setUnsigned()
            ->setAutoIncrement()
        );

        $pathsTable->addField(
            (new FieldScriptSpitter("path"))
            ->setType("VARCHAR(255)")
        );

        $onScript .= $pathsTable->getScript();

        return $onScript;
    }

    public function getRollbackSql(): string
    {
        return sprintf("DROP TABLE %s;", self::TABLE_NAME);
    }
}
