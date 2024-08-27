<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Migrations;

use Danilocgsilva\EndpointsCatalog\Models\{Path, Dns, DnsPath};

class Rollback
{
    public function getString(): string
    {
        $rollbackString = sprintf("DROP TABLE %s;", DnsPath::TABLENAME) . PHP_EOL;
        $rollbackString .= sprintf("DROP TABLE %s;", Dns::TABLENAME) . PHP_EOL;
        $rollbackString .= sprintf("DROP TABLE %s;", Path::TABLENAME);

        return $rollbackString;
    }
}
