<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Migrations;
use Danilocgsilva\ClassToSqlSchemaScript\TableScriptSpitter;
use Danilocgsilva\ClassToSqlSchemaScript\FieldScriptSpitter;

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
            (new FieldScriptSpitter("dbs_id"))
            ->setType("INT")
            ->setNotNull()
            ->setUnsigned()
        );

        $dnsTable->addField(
            (new FieldScriptSpitter("path_id"))
            ->setType("INT")
            ->setNotNull()
            ->setUnsigned()
        );

        return $dnsTable->getScript();
    }
}
