<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Models;

use Danilocgsilva\EndpointsCatalog\Pattern\TraitModel;

final class Dns
{
    use TraitModel;
    
    public const TABLENAME = "dns";

    public readonly string $dns;

    public function setDns(string $dns): self
    {
        $this->dns = $dns;
        return $this;
    }
}
