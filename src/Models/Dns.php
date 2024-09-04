<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Models;

use Danilocgsilva\EndpointsCatalog\Pattern\TraitModel;

final class Dns
{
    use TraitModel;
    
    public const TABLENAME = "dns";

    public readonly int $id;

    public readonly string $dns;

    public readonly string $description;

    public readonly string $port;

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

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

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }
}
