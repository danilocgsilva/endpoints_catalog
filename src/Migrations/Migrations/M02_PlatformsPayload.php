<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Migrations\Migrations;

use Danilocgsilva\EndpointsCatalog\Migrations\MigrationInterface;
use Danilocgsilva\EndpointsCatalog\Migrations\Migrations\FacadeComponents\PayloadsTable;
use Danilocgsilva\EndpointsCatalog\Migrations\Migrations\FacadeComponents\PlatformsTable;

class M02_PlatformsPayload implements MigrationInterface
{
    private PlatformsTable $platformsTable;

    private PayloadsTable $payloadsTable;
    
    public function __construct()
    {
        $this->platformsTable = new PlatformsTable();
        $this->payloadsTable = new PayloadsTable();
    }
    
    public function getString(): string
    {
        $platformScript = $this->platformsTable->getString();

        $payloadsTableScript = $this->payloadsTable->getString();

        return $platformScript . PHP_EOL . $payloadsTableScript;
    }

    public function getRollbackString(): string
    {
        $payloadScriptRollback = $this->payloadsTable->getRollbackString();
        
        $platformScriptRollback = $this->platformsTable->getRollbackString();

        return $payloadScriptRollback . PHP_EOL . $platformScriptRollback;
    }
}
