<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Migrations;
use Danilocgsilva\ClassToSqlSchemaScript\TableScriptSpitter;
use Danilocgsilva\ClassToSqlSchemaScript\FieldScriptSpitter;

class Migrations
{
    private TableScriptSpitter $tableScriptSpitter;

    const TABLE_NAME = "paths";
    
    public function __construct()
    {
        $this->tableScriptSpitter = new TableScriptSpitter(self::TABLE_NAME);
    }
    
    public function getOnSql(): string
    {
        $this->tableScriptSpitter->addField(
            (new FieldScriptSpitter("id"))
            ->setType("INT")
            ->setNotNull()
            ->setPrimaryKey()
            ->setUnsigned()
            ->setAutoIncrement()
        );

        $this->tableScriptSpitter->addField(
            (new FieldScriptSpitter("path"))
            ->setType("VARCHAR(255)")
        );

        return $this->tableScriptSpitter->getScript();
    }

    public function getRollbackSql(): string
    {
        return sprintf("DROP TABLE %s;", self::TABLE_NAME);
    }
}
