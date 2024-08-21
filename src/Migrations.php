<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Migrations;
use Danilocgsilva\ClassToSqlSchemaScript\DatabaseScriptSpitter;

class Migrations
{
    private DatabaseScriptSpitter $databaseScriptSpitter;
    
    public function __construct()
    {
        $this->databaseScriptSpitter = new DatabaseScriptSpitter();
    }
    
    public function on()
    {

    }

    public function rollback()
    {

    }
}
