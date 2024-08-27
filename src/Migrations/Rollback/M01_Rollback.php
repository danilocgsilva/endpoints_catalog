<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Migrations\Rollback;

use Danilocgsilva\EndpointsCatalog\Models\{Path, Dns, DnsPath};
use Danilocgsilva\EndpointsCatalog\Migrations\MigrationInterface;

class M01_Rollback implements MigrationInterface
{
    public function getString(): string
    {
        $rollbackString = sprintf("DROP TABLE %s;", DnsPath::TABLENAME) . PHP_EOL;
        $rollbackString .= sprintf("DROP TABLE %s;", Dns::TABLENAME) . PHP_EOL;
        $rollbackString .= sprintf("DROP TABLE %s;", Path::TABLENAME);

        return $rollbackString;
    }
}
