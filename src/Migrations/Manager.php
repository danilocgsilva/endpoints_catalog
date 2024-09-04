<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Migrations;

use Danilocgsilva\EndpointsCatalog\Migrations\Evolve\M02_Platforms;

class Manager
{
    private function getNextMigration(): string
    {
        return M02_Platforms::class;
    }
}
