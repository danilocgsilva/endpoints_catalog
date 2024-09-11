<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Migrations;

interface MigrationInterface
{
    public function getString(): string;

    public function getRollbackString(): string;

    public function getTablesNames(): array;
}
