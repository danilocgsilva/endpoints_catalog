<?php

declare(strict_types=1);

namespace Tests\Repositories;

use Danilocgsilva\EndpointsCatalog\Repositories\DnsPathRepository;
use PHPUnit\Framework\TestCase;
use Tests\Utils;
use Danilocgsilva\EndpointsCatalog\Models\DnsPath;
use Danilocgsilva\EndpointsCatalog\Repositories\Interfaces\BaseRepositoryInterface;

class DnsPathRepositoryTest extends TestCase
{
    private Utils $dbUtils;

    /**
     * @var BaseRepositoryInterface<DnsPath> $repository
     */
    private DnsPathRepository $repository;

    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->dbUtils = new Utils();
    }

    public function setUp(): void
    {
        $this->repository = new DnsPathRepository($this->dbUtils->getPdo());
    }

    public function testSave(): void
    {
        $this->assertSame(0, $this->dbUtils->getTableCount('dns_path'));
        $this->fillDnsAndPathTables();
        
        $dns = new DnsPath();
        $dns->setDnsId(1)->setPathId(1);
        $this->repository->save($dns);
        $this->assertSame(1, $this->dbUtils->getTableCount('dns_path'));
    }

    public function testGet(): void
    {
        $this->cleanTables();
        $this->fillDnsAndPathTables();

        $this->assertSame(0, $this->dbUtils->getTableCount('dns_path'));
        $this->dbUtils->fillTable('dns_path', [
            [
                "path_id" => 1,
                "dns_id" => 1
            ]
        ]);
        $this->assertSame(1, $this->dbUtils->getTableCount('dns_path'));

        $recoveredDnsPath = $this->repository->get(1);
        $this->assertSame(1, $recoveredDnsPath->path_id);
        $this->assertSame(1, $recoveredDnsPath->dns_id);
    }

    public function testReplace(): void
    {
        $this->cleanTables();
        $this->fillDnsAndPathTables();
        $this->dbUtils->fillTable('dns', [
            ["dns" => "myowndns.com"]
        ]);

        $this->dbUtils->fillTable('dns_path', [
            [
                "path_id" => 1,
                "dns_id" => 1
            ]
        ]);

        $toReplacePath = (new DnsPath())->setDnsId(2);
        $this->repository->replace(1, $toReplacePath);

        $recoveredAfterReplace = $this->repository->get(1);

        $this->assertSame(2, $recoveredAfterReplace->dns_id);
    }

    public function testDelete(): void
    {
        $this->cleanTables();
        $this->fillDnsAndPathTables();

        $this->dbUtils->fillTable('dns_path', [
            [
                "path_id" => 1,
                "dns_id" => 1
            ]
        ]);

        $this->assertSame(1, $this->dbUtils->getTableCount('dns_path'));
        $this->repository->delete(1);
        $this->assertSame(0, $this->dbUtils->getTableCount('dns_path'));
    }

    public function testList(): void
    {
        $this->cleanTables();
        $this->fillDnsAndPathTables();
        $this->dbUtils->fillTable('dns', [
            ["dns" => "myowndns.com"]
        ]);

        $this->dbUtils->fillTable('dns_path', [
            [
                "path_id" => 1,
                "dns_id" => 1
            ],
            [
                "path_id" => 1,
                "dns_id" => 2
            ]
        ]);
        $this->assertSame(2, $this->dbUtils->getTableCount('dns_path'));
        /**
         * @var array<DnsPath> $listOfDnsPath
         */
        $listOfDnsPath = $this->repository->list();
        $this->assertCount(2, $listOfDnsPath);
    }

    private function cleanTables(): void
    {
        $this->dbUtils->cleanTable('dns_path');
        $this->dbUtils->cleanTable('dns');
        $this->dbUtils->cleanTable('paths');
    }

    private function fillDnsAndPathTables(): void
    {
        $this->dbUtils->fillTable('dns', [
            ["dns" => "leftington.com"]
        ]);
        $this->dbUtils->fillTable('paths', [
            ["path" => "my/first"],
        ]);
    }
}
