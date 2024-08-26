<?php

declare(strict_types=1);

namespace Tests\Repositories;

use PHPUnit\Framework\TestCase;
use Danilocgsilva\EndpointsCatalog\Repositories\PathRepository;
use Tests\Utils;
use Danilocgsilva\EndpointsCatalog\Models\Path;
use Danilocgsilva\EndpointsCatalog\Repositories\Interfaces\BaseRepositoryInterface;

class PathRepositoryTest extends TestCase
{
    private Utils $dbUtils;

    /** @var BaseRepositoryInterface<Path> $repository */
    private PathRepository $repository;
    
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->dbUtils = new Utils();
    }

    public function setUp(): void
    {
        $this->repository = new PathRepository($this->dbUtils->getPdo());
    }
    
    public function testSave(): void
    {
        $this->cleanTables();
        
        $this->assertSame(0, $this->dbUtils->getTableCount('paths'));
        $path = new Path();
        $path->setPath("my/humble/path");
        $this->repository->save($path);
        $this->assertSame(1, $this->dbUtils->getTableCount('paths'));
    }

    public function testGet(): void
    {
        $this->cleanTables();

        $this->assertSame(0, $this->dbUtils->getTableCount('paths'));
        $this->dbUtils->fillTable('paths', [
            [
                "path" => "another/path"
            ]
        ]);
        $this->assertSame(1, $this->dbUtils->getTableCount('paths'));

        $recoveredPath = $this->repository->get(1);
        $this->assertSame("another/path", $recoveredPath->path);
        $this->assertSame(1, $recoveredPath->id);
    }

    public function testReplace(): void
    {
        $this->cleanTables();
        
        $this->dbUtils->cleanTable('paths');
        $this->dbUtils->fillTable('paths', [
            [
                "path" => "another/path"
            ]
        ]);

        $toReplacePath = (new Path())->setPath("another/path/1");
        $this->repository->replace(1, $toReplacePath);

        $recoveredAfterReplace = $this->repository->get(1);

        $this->assertSame("another/path/1", $recoveredAfterReplace->path);
    }

    public function testDelete(): void
    {
        $this->cleanTables();

        $this->dbUtils->fillTable('paths', [
            [
                "path" => "another/path"
            ]
        ]);
        $this->assertSame(1, $this->dbUtils->getTableCount('paths'));
        $this->repository->delete(1);
        $this->assertSame(0, $this->dbUtils->getTableCount('paths'));
    }

    public function testList(): void
    {
        $this->cleanTables();

        $this->dbUtils->fillTable('paths', [
            ["path" => "my/first"],
            ["path" => "the/second/path"]
        ]);
        $this->assertSame(2, $this->dbUtils->getTableCount('paths'));
        $listOfPaths = $this->repository->list();
        $this->assertCount(2, $listOfPaths);
        $this->assertSame(1, $listOfPaths[0]->id);
        $this->assertSame(2, $listOfPaths[1]->id);
    }

    private function cleanTables(): void
    {
        $this->dbUtils->cleanTable('dns_path');
        $this->dbUtils->cleanTable('paths');
    }
}
