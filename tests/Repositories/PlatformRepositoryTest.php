<?php

declare(strict_types=1);

namespace Tests\Repositories;

use Danilocgsilva\EndpointsCatalog\Repositories\PlatformRepository;
use PHPUnit\Framework\TestCase;
use Tests\Utils;
use Danilocgsilva\EndpointsCatalog\Models\Platform;
use Danilocgsilva\EndpointsCatalog\Repositories\Interfaces\BaseRepositoryInterface;

class PlatformRepositoryTest extends TestCase
{
    private Utils $dbUtils;

    /**
     * @var BaseRepositoryInterface<Platform> $repository
     */
    private PlatformRepository $platformRepository;

    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->dbUtils = new Utils();
    }

    public function setUp(): void
    {
        $this->platformRepository = new PlatformRepository($this->dbUtils->getPdo());
    }

    public function testSave(): void
    {
        $platformName = "Business drive";
        
        $this->assertSame(0, $this->dbUtils->getTableCount('platforms'));
        $this->fillDnsAndPathTables();
        
        $platform = new Platform();
        $platform->setName($platformName);
        $this->platformRepository->save($platform);
        $this->assertSame(1, $this->dbUtils->getTableCount('platforms'));
    }

    public function testGet(): void
    {
        $this->cleanTables();

        $this->assertSame(0, $this->dbUtils->getTableCount('platforms'));
        $this->dbUtils->fillTable('platforms', [
            [
                "name" => "Business drive"
            ]
        ]);
        $this->assertSame(1, $this->dbUtils->getTableCount('platforms'));

        $retrievedPlatoform = $this->platformRepository->get(1);
        $this->assertSame("Business drive", $retrievedPlatoform->name);
    }

    public function testBothFieldsTest(): void
    {
        $this->cleanTables();
        $this->assertSame(0, $this->dbUtils->getTableCount('platforms'));
        $this->dbUtils->fillTable('platforms', [
            [
                "name" => "Business drive",
                "description" => "A platoform business related to driving",
            ]
        ]);

        $retrievedPlatoform = $this->platformRepository->get(1);
        $this->assertSame("Business drive", $retrievedPlatoform->name);
        $this->assertSame("A platoform business related to driving", $retrievedPlatoform->description);
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
        /** @var array<DnsPath> $listOfDnsPath */
        $listOfDnsPath = $this->repository->list();
        $this->assertCount(2, $listOfDnsPath);
    }

    public function testSaveEndpoint(): void
    {
        $this->cleanTables();
        $this->fillDnsAndPathTables();
        $this->assertSame(0, $this->dbUtils->getTableCount('dns_path'));

        $this->dbUtils->getPathRepository()->saveAndAssingId(
            ($pathModel = new Path())
            ->setPath("/my/groceries")
        );

        $this->dbUtils->getDnsRepository()->saveAndAssingId(
            ($dnsModel = new Dns())
            ->setDns("rubens.com.br")
        );

        $this->repository->saveEndpoint($dnsModel, $pathModel);
        $this->assertSame(1, $this->dbUtils->getTableCount('dns_path'));
    }

    private function cleanTables(): void
    {
        $this->dbUtils->cleanTable('platforms');
    }

    private function fill(): void
    {
        $this->dbUtils->fillTable('platform', [
            ["name" => "Business drive"]
        ]);
    }
}
