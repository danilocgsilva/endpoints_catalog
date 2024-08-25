<?php

declare(strict_types=1);

namespace Tests\Services;

use Danilocgsilva\EndpointsCatalog\Services\EndpointService;
use PHPUnit\Framework\TestCase;
use Danilocgsilva\EndpointsCatalog\Models\{
    Path, Dns
};

class EndpointServiceTest extends TestCase
{
    public function testGetEndpointString(): void
    {
        $dns = (new Dns())
        ->setDns("camila.com.br");

        $path = (new Path())
        ->setPath("sweeties/1");
        
        $endpointService = new EndpointService($dns, $path);

        $this->assertSame("camila.com.br/sweeties/1", $endpointService->getEndpointString());
    }

    public function testSlashGetEndpointString(): void
    {
        $dns = (new Dns())
        ->setDns("johndoestuff.com");

        $path = (new Path())
        ->setPath("/homes/9");
        
        $endpointService = new EndpointService($dns, $path);

        $this->assertSame("johndoestuff.com/homes/9", $endpointService->getEndpointString());
    }
}
