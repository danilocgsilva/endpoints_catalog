<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Models;

use Danilocgsilva\EndpointsCatalog\Pattern\TraitModel;

final class Path
{
    use TraitModel;
    
    public const TABLENAME = "paths";

    public readonly string $path;
}
