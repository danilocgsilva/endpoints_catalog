<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Migrations\Migrations;

use Danilocgsilva\ClassToSqlSchemaScript\TableScriptSpitter;
use Danilocgsilva\ClassToSqlSchemaScript\FieldScriptSpitter;
use Danilocgsilva\ClassToSqlSchemaScript\ForeignKeyScriptSpitter;
use Danilocgsilva\EndpointsCatalog\Models\{Path, Dns, DnsPath};
use Danilocgsilva\EndpointsCatalog\Migrations\MigrationInterface;

class M01_Apply implements MigrationInterface
{
    private array $tables;
    
    public function __construct()
    {
        $this->tables = [
            Path::TABLENAME,
            Dns::TABLENAME,
            DnsPath::TABLENAME
        ];
    }
    
    public function getString(): string
    {
        $onScript = "";

        $onScript .= $this->getPathsTableScript() . PHP_EOL;
        $onScript .= $this->getDnsTableScript() . PHP_EOL;
        $onScript .= $this->getDnsPathTableScript() . PHP_EOL;
        $onScript .= $this->getForeignKeys();

        return $onScript;
    }

    public function getRollbackString(): string
    {
        $dropTableQueries = [];
        for ($i = count($this->tables) - 1; $i >= 0; $i--) {
            $dropTableQueries = sprintf("DROP TABLE %s;", $this->tables[$i]);
        }

        return implode(PHP_EOL, $dropTableQueries);
    }

    private function getPathsTableScript(): string
    {
        $pathsTable = new TableScriptSpitter($this->tables[0]);
        
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

    public function getTablesNames(): array
    {
        return $this->tables;
    }

    private function getDnsTableScript(): string
    {
        $dnsTable = new TableScriptSpitter($this->tables[1]);

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

        $dnsTable->addField(
            (new FieldScriptSpitter("description"))
                ->setType("VARCHAR(255)")
        );

        return $dnsTable->getScript();
    }

    private function getDnsPathTableScript(): string
    {
        $dnsTable = new TableScriptSpitter($this->tables[2]);

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
