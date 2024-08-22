<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Models;

use Danilocgsilva\EndpointsCatalog\Pattern\TraitModel;

final class DnsPath
{
    use TraitModel;
    
    public const TABLENAME = "dns_path";

    public readonly int $path_id;

    public readonly int $dns_id;
}
