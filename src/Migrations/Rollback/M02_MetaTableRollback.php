<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Migrations\Rollback;

use Danilocgsilva\EndpointsCatalog\Migrations\MigrationInterface;

class M02_MetaTableRollback implements MigrationInterface
{
    public function getString(): string
    {
        return "DROP TABLE migrations;";
    }
}
