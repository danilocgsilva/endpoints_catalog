<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Pattern;

trait TraitModel
{
    public static function getTableName(): string
    {
        return self::TABLENAME;
    }
}
