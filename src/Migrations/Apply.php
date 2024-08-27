<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Migrations;

use Danilocgsilva\ClassToSqlSchemaScript\TableScriptSpitter;
use Danilocgsilva\ClassToSqlSchemaScript\FieldScriptSpitter;
use Danilocgsilva\ClassToSqlSchemaScript\ForeignKeyScriptSpitter;
use Danilocgsilva\EndpointsCatalog\Models\{Path, Dns, DnsPath};

class Apply
{
    public function getString(): string
    {
        $onScript = "";

        $onScript .= $this->getPathsTableScript() . PHP_EOL;
        $onScript .= $this->getDnsTableScript() . PHP_EOL;
        $onScript .= $this->getDnsPathTableScript() . PHP_EOL;
        $onScript .= $this->getForeignKeys();

        return $onScript;
    }

    private function getPathsTableScript(): string
    {
        $pathsTable = new TableScriptSpitter(Path::TABLENAME);
        
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
        $dnsTable = new TableScriptSpitter(Dns::TABLENAME);

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

        $dnsTable->addField(
            (new FieldScriptSpitter("port"))
            ->setType("CHAR(5)")
        );

        return $dnsTable->getScript();
    }

    private function getDnsPathTableScript(): string
    {
        $dnsTable = new TableScriptSpitter(DnsPath::TABLENAME);

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
            ->setTable(DnsPath::TABLENAME)
            ->setConstraintName('path_id_dns_path_foreign')
            ->setForeignTable(Path::TABLENAME)
            ->setTableForeignkey('id')
            ->setForeignKey('path_id');

        $dnsDnsForeign = (new ForeignKeyScriptSpitter())
            ->setTable(DnsPath::TABLENAME)
            ->setConstraintName('dns_id_dns_foreign')
            ->setForeignTable(Dns::TABLENAME )
            ->setTableForeignkey('id')
            ->setForeignKey('dns_id');

        return $dnsPathForeign->getScript() . PHP_EOL . $dnsDnsForeign->getScript();
    }
}
