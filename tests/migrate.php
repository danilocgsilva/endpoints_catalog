<?php

declare(strict_types=1);

use Tests\Utils;
use Danilocgsilva\EndpointsCatalog\Migrations\Manager;
use Exception;

require("../vendor/autoload.php");

if (count($argv) < 3) {
    die("You need to tell which operation you want and the migration to run." . PHP_EOL);
}

if (!in_array($argv[1], ['migrate', 'rollback'])) {
    throw new Exception("Please, as the first argument, just migrate or rollback are accepted. You give " . $argv[1] . ".");
}

$utils = new Utils();
$migrationManager = new Manager($utils->getDatabaseName(), $utils->getPdo());

$migrate = new $argv[2]();

switch ($argv[1]) {
    case "migrate":
        $utils->migrate($migrate);
        break;
    case "rollback":
        $utils->migrateRollback($migrate);
        break;
}

