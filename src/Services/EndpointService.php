<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Services;

use Danilocgsilva\EndpointsCatalog\Models\{Path, Dns};

class EndpointService
{
    public function __construct(public readonly Dns $dns, public readonly Path $path) {}

    public function getEndpointString(): string
    {
        $portSuffix = isset($this->dns->port) ? ":{$this->dns->port}" : '';
        $pathFromModel = ltrim($this->path->path, '/');
    
        return "{$this->dns->dns}{$portSuffix}/{$pathFromModel}";
    }
}
