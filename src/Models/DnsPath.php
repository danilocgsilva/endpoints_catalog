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

    public readonly int $id;

    public function setDnsId(int $dns_id): self
    {
        $this->dns_id = $dns_id;
        return $this;
    }

    public function setPathId(int $path_id): self
    {
        $this->path_id = $path_id;
        return $this;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }
}
