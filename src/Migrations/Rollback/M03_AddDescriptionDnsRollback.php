<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Migrations\Apply;

use Danilocgsilva\EndpointsCatalog\Migrations\MigrationInterface;
use Danilocgsilva\EndpointsCatalog\Models\Path;

class M03_AddDescriptionDnsRollback implements MigrationInterface
{
    public function getString(): string
    {
        return sprintf("ALTER TABLE %s DROP `description`;", Path::TABLENAME);
    }
}
