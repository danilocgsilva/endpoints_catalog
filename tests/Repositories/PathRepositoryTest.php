<?php

declare(strict_types=1);

namespace Tests\Repositories;

use PHPUnit\Framework\TestCase;
use Danilocgsilva\EndpointsCatalog\Repositories\PathRepository;
use PDO;
use Tests\Utils;
use Danilocgsilva\EndpointsCatalog\Models\Path;

class PathRepositoryTest extends TestCase
{
    private PDO $pdo;

    private Utils $dbUtils;
    
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->dbUtils = new Utils();
        $this->pdo = $this->dbUtils->getPdo();
    }
    
    public function testSave(): void
    {   $this->dbUtils->cleanTable('paths');
        $this->assertSame(0, $this->dbUtils->getTableCount('paths'));
        $pathsRepository = new PathRepository($this->pdo);
        $path = new Path();
        $path->setPath("my/humble/path");
        $pathsRepository->save($path);
        $this->assertSame(1, $this->dbUtils->getTableCount('paths'));
    }

    public function testGet(): void
    {
        $this->dbUtils->cleanTable('paths');
        $this->assertSame(0, $this->dbUtils->getTableCount('paths'));
        $this->dbUtils->fillTable('paths', [
            [
                "path" => "another/path"
            ]
        ]);
        $this->assertSame(1, $this->dbUtils->getTableCount('paths'));
    }
}
