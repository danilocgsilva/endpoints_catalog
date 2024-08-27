<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Migrations;

class MigrationManager
{
    public function getNextMigrationClass(): string
    {
        return "Danilocgsilva\EndpointsCatalog\Migrations\Apply\M02_MetaTable";
    }
}
