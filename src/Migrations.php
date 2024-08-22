<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog;
use Danilocgsilva\ClassToSqlSchemaScript\TableScriptSpitter;
use Danilocgsilva\ClassToSqlSchemaScript\FieldScriptSpitter;
use Danilocgsilva\ClassToSqlSchemaScript\ForeignKeyScriptSpitter;

class Migrations
{
    const PATHS_TABLE = "paths";

    const DNS_TABLE = "dns";

    const DNS_PATH_TABLE = "dns_path";
    
    public function getOnSql(): string
    {
        $onScript = "";

        $onScript .= $this->getPathsTableScript();
        $onScript .= $this->getDnsTableScript();
        $onScript .= $this->getDnsPathScript();
        $onScript .= $this->getForeignKeys();

        return $onScript;
    }

    public function getRollbackSql(): string
    {
        $rollbackString = sprintf("DROP TABLE %s;", self::DNS_PATH_TABLE);
        $rollbackString .= sprintf("DROP TABLE %s;", self::DNS_TABLE);
        $rollbackString .= sprintf("DROP TABLE %s;", self::PATHS_TABLE);

        return $rollbackString;
    }

    private function getPathsTableScript(): string
    {
        $pathsTable = new TableScriptSpitter(self::PATHS_TABLE);
        
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

        return $pathsTable->getScript();
    }

    private function getDnsTableScript(): string
    {
        $dnsTable = new TableScriptSpitter(self::DNS_TABLE);

        $dnsTable->addField(
            (new FieldScriptSpitter("id"))
            ->setPrimaryKey()
            ->setType("INT")
            ->setNotNull()
            ->setUnsigned()
            ->setAutoIncrement()
        );

        $dnsTable->addField(
            (new FieldScriptSpitter("dns"))
            ->setType("VARCHAR(255)")
        );

        return $dnsTable->getScript();
    }

    private function getDnsPathScript(): string
    {
        $dnsTable = new TableScriptSpitter(self::DNS_PATH_TABLE);

        $dnsTable->addField(
            (new FieldScriptSpitter("id"))
            ->setType("INT")
            ->setNotNull()
            ->setUnsigned()
            ->setAutoIncrement()
            ->setPrimaryKey()
        );

        $dnsTable->addField(
            (new FieldScriptSpitter("path_id"))
            ->setType("INT")
            ->setNotNull()
            ->setUnsigned()
        );

        $dnsTable->addField(
            (new FieldScriptSpitter("dns_id"))
            ->setType("INT")
            ->setNotNull()
            ->setUnsigned()
        );

        return $dnsTable->getScript();
    }

    private function getForeignKeys(): string
    {
        $dnsPathForeign = (new ForeignKeyScriptSpitter())
            ->setTable(self::DNS_PATH_TABLE)
            ->setConstraintName('path_id_dns_path_foreign')
            ->setForeignTable(self::PATHS_TABLE)
            ->setForeignKey('id');

        $dnsDnsForeign = (new ForeignKeyScriptSpitter())
            ->setTable(self::DNS_PATH_TABLE)
            ->setConstraintName('dns_id_dns_foreign')
            ->setForeignTable(self::DNS_TABLE )
            ->setForeignKey('id');

        return $dnsPathForeign->getScript() . PHP_EOL . $dnsDnsForeign->getScript();
    }
}
