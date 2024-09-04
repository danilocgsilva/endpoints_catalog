<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Migrations\Apply;

use Danilocgsilva\EndpointsCatalog\Migrations\MigrationInterface;
use Danilocgsilva\EndpointsCatalog\Models\Path;

class M03_AddDescriptionDns implements MigrationInterface
{
    public function getString(): string
    {
        return sprintf("ALTER TABLE %s ADD `description` VARCHAR(192);", Path::TABLENAME);
    }
}
