<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Models;

use Danilocgsilva\EndpointsCatalog\Pattern\TraitModel;

final class Dns
{
    use TraitModel;
    
    public const TABLENAME = "dns";

    public readonly string $dns;
}
