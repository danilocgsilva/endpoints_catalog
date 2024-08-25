<?php

declare(strict_types=1);

use Tests\Utils;

require("../vendor/autoload.php");

$utils = new Utils();
$utils->migrate();
