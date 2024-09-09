<?php

declare(strict_types=1);

use Tests\Utils;
use Danilocgsilva\EndpointsCatalog\Migrations\Manager;

require("../vendor/autoload.php");

$utils = new Utils();
$migrationManager = new Manager($utils->getPdo());
$utils->migrate();
