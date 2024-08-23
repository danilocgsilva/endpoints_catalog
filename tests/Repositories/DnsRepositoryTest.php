<?php

declare(strict_types=1);

namespace Tests\Repositories;

use Danilocgsilva\EndpointsCatalog\Repositories\DnsRepository;
use PHPUnit\Framework\TestCase;
use Tests\Utils;
use Danilocgsilva\EndpointsCatalog\Models\Dns;
use Danilocgsilva\EndpointsCatalog\Repositories\Interfaces\BaseRepositoryInterface;

class DnsRepositoryTest extends TestCase
{
    /**
     * @var BaseRepositoryInterface<Dns> $repository
     */
    private DnsRepository $repository;

    private Utils $dbUtils;

    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->dbUtils = new Utils();
    }

    public function setUp(): void
    {
        $this->repository = new DnsRepository($this->dbUtils->getPdo());
    }

    public function testSave(): void
    {   
        $this->cleanTables();
        
        $this->assertSame(0, $this->dbUtils->getTableCount('dns'));
        $dns = new Dns();
        $dns->setDns("hoogle.com");
        $this->repository->save($dns);
        $this->assertSame(1, $this->dbUtils->getTableCount('dns'));
    }

    public function testGet(): void
    {
        $this->cleanTables();

        $this->assertSame(0, $this->dbUtils->getTableCount('dns'));
        $this->dbUtils->fillTable('dns', [
            [
                "dns" => "hookle.com"
            ]
        ]);
        $this->assertSame(1, $this->dbUtils->getTableCount('dns'));

        $recoveredPath = $this->repository->get(1);
        $this->assertSame("hookle.com", $recoveredPath->dns);
    }

    public function testReplace(): void
    {
        $this->cleanTables();

        $this->dbUtils->fillTable('dns', [
            [
                "dns" => "dookle.com"
            ]
        ]);

        $toReplacePath = (new Dns())->setDns("hoople.com");
        $this->repository->replace(1, $toReplacePath);

        $recoveredAfterReplace = $this->repository->get(1);

        $this->assertSame("hoople.com", $recoveredAfterReplace->dns);
    }

    public function testDelete(): void
    {
        $this->cleanTables();

        $this->dbUtils->fillTable('dns', [
            [
                "dns" => "coojle.com"
            ]
        ]);
        $this->assertSame(1, $this->dbUtils->getTableCount('dns'));
        $this->repository->delete(1);
        $this->assertSame(0, $this->dbUtils->getTableCount('dns'));
    }

    public function testList(): void
    {
        $this->cleanTables();

        $this->dbUtils->fillTable('dns', [
            ["dns" => "leftington.com"],
            ["dns" => "doocle.com"]
        ]);
        $this->assertSame(2, $this->dbUtils->getTableCount('dns'));
        /**
         * @var array<Dns> $listOfDns
         */
        $listOfDns = $this->repository->list();
        $this->assertCount(2, $listOfDns);
    }

    private function cleanTables(): void
    {
        $this->dbUtils->cleanTable('dns_path');
        $this->dbUtils->cleanTable('dns');
    }
}
