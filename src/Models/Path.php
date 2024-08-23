<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Models;

use Danilocgsilva\EndpointsCatalog\Pattern\TraitModel;

final class Path implements ModelsInterface
{
    use TraitModel;
    
    public const TABLENAME = "paths";

    public readonly string $path;

    public function setPath(string $path): self
    {
        $this->path = $path;
        return $this;
    }
}
