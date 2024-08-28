<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog;

use Exception;

class NoMigrationsLeftException extends Exception
{
    protected string $message = "There's no migrations left.";
}