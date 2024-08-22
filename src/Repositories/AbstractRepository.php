<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Repositories;

use PDO;

abstract class AbstractRepository
{
    public function __construct(private PDO $pdo) 
    {
    }
}
