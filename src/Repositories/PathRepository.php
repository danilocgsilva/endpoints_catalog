<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Repositories;

use PDO;
use Danilocgsilva\EndpointsCatalog\Models\Path;
use Danilocgsilva\EndpointsCatalog\Models\ModelsInterface;
use Danilocgsilva\EndpointsCatalog\Repositories\Interfaces\BaseRepositoryInterface;

class PathRepository extends AbstractRepository implements BaseRepositoryInterface
{
    public const MODEL = Path::class;

    public function save(ModelsInterface $model): void
    {
        $this->pdo->prepare(
            sprintf("INSERT INTO %s (path) VALUES (:path)", self::MODEL::TABLENAME)
        )->execute([
            ':path' => $model->path
        ]);
    }

    public function get(int $id): ModelsInterface
    {
        $preResults = $this->pdo->prepare(
            sprintf("SELECT path FROM %s WHERE id = :id;", self::MODEL::TABLENAME)
        );
        $preResults->setFetchMode(PDO::FETCH_NUM);
        $preResults->execute([':id' => $id]);
        $fetchedData = $preResults->fetch();
        return (new Path())
            ->setPath($fetchedData[0]);
    }

    public function replace(int $id, ModelsInterface $model): void
    {
        $query = sprintf(
            "UPDATE %s SET path = :path WHERE id = :id;",
            self::MODEL::TABLENAME
        );

        $this->pdo->prepare($query)->execute([
            ':path' => $model->path,
            ':id' => $id
        ]);
    }

    public function delete(int $id): void
    {
        $this->pdo->prepare(
            sprintf("DELETE FROM %s WHERE id = :id", self::MODEL::TABLENAME)
        )->execute();
    }

    public function list(): array
    {
        $query = sprintf(
            "SELECT %s FROM %s;",
            "path",
            self::MODEL::TABLENAME
        );

        $preResults = $this->pdo->prepare($query);
        $preResults->setFetchMode(PDO::FETCH_NUM);
        $preResults->execute();
        $dnsRepositoryList = [];
        while ($row = $preResults->fetch()) {
            $dnsRepositoryList[] = (new Path())->setPath($row[0]);
        }
        return $dnsRepositoryList; 
    }
}
