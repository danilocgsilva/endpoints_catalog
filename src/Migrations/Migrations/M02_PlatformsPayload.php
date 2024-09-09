<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Migrations\Migrations;

use Danilocgsilva\ClassToSqlSchemaScript\{
    AlterTableScriptSpitter, 
    FieldScriptSpitter, 
    ForeignKeyScriptSpitter,
    TableScriptSpitter
};
use Danilocgsilva\EndpointsCatalog\Migrations\MigrationInterface;
use Danilocgsilva\EndpointsCatalog\Models\{Dns, Payload, Platform};
use Danilocgsilva\EndpointsCatalog\Migrations\Migrations\FacadeComponents\PlatformsTable;

class M02_PlatformsPayload implements MigrationInterface
{
    private PlatformsTable $platformsTable;
    
    public function __construct()
    {
        $this->platformsTable = new PlatformsTable();
    }
    
    public function getString(): string
    {
        $platformScript = $this->platformsTable->getString();

        $payloadsTableScript = $this->getPayloadSqlScript();

        return $platformScript . PHP_EOL . $payloadsTableScript;
    }

    public function getRollbackString(): string
    {
        return $this->platformsTable->getRollbackString();
    }

    private function getPayloadSqlScript(): string
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
}
