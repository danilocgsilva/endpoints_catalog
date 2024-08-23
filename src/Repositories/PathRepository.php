<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Repositories;

use Danilocgsilva\EndpointsCatalog\Pattern\TraitModel;
use PDO;
use Danilocgsilva\EndpointsCatalog\Models\Path;

class PathRepository extends AbstractRepository implements RepositoryInterface
{
    public const MODEL = Path::class;

    public function save(TraitModel $model): void
    {
        $this->pdo->prepare(
            sprintf("INSERT INTO %s (path) VALUES (:path)", self::MODEL::TABLENAME)
        )->execute([
            ':path' => $model->path
        ]);
    }

    public function get(int $id): TraitModel
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

    public function replace(int $id, TraitModel $model): void
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

    }

    public function list(): array
    {

    }
}
