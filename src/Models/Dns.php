<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Models;

use Danilocgsilva\EndpointsCatalog\Pattern\TraitModel;

final class Dns
{
    use TraitModel;
    
    public const TABLENAME = "dns";

    public readonly string $dns;

    public readonly string $port;

    public function setDns(string $dns): self
    {
        $this->dns = $dns;
        return $this;
    }

    public function setPort(string $port): self
    {
        $this->port = $port;
        return $this;
    }
}
