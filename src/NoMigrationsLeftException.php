<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog;

use Exception;
use Throwable;

class NoMigrationsLeftException extends Exception
{
    public function __construct(
        string $message = "No migrations left.", 
        int $code = 0, 
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}