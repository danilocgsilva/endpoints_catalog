<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Models;

use Danilocgsilva\EndpointsCatalog\Pattern\TraitModel;
use Danilocgsilva\EndpointsCatalog\Models\Path;

class Payload
{
    use TraitModel;

    public const TABLENAME = "payloads";

    public readonly int $id;

    public readonly string $payload;

    public readonly string $name;

    public readonly Path $path;

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setPayload(string $payload): self
    {
        $this->payload = $payload;
        return $this;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setPath(Path $path): self
    {
        $this->path = $path;
        return $this;
    }
}
