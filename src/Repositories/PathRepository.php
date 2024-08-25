<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Repositories;

use PDO;
use Danilocgsilva\EndpointsCatalog\Models\Path;
use Danilocgsilva\EndpointsCatalog\Repositories\Interfaces\BaseRepositoryInterface;

/**
 * @template-implements BaseRepositoryInterface<Path>
 */
class PathRepository extends AbstractRepository implements BaseRepositoryInterface
{
    public const MODEL = Path::class;

    /**
     * @param Path $pathModel
     * @return void
     */
    public function save($pathModel): void
    {
        $this->pdo->prepare(
            sprintf("INSERT INTO %s (path) VALUES (:path)", self::MODEL::TABLENAME)
        )->execute([
            ':path' => $pathModel->path
        ]);
    }

    /**
     * @param Path $pathModel
     * @return void
     */
    public function saveAndAssingId($pathModel): void
    {
        $this->save($pathModel);
        $preResults = $this->pdo->prepare("SELECT LAST_INSERT_ID();");
        $preResults->execute();
        $pathModel->setId(
            $preResults->fetch(PDO::FETCH_NUM)[0]
        );
    }

    /**
     * @param int $id
     * @return \Danilocgsilva\EndpointsCatalog\Models\Path
     */
    public function get(int $id): Path
    {
        $preResults = $this->pdo->prepare(
            sprintf("SELECT `path` FROM %s WHERE id = :id;", self::MODEL::TABLENAME)
        );
        $preResults->setFetchMode(PDO::FETCH_NUM);
        $preResults->execute([':id' => $id]);
        $fetchedData = $preResults->fetch();
        return (new Path())
            ->setPath($fetchedData[0]);
    }

    /**
     * @param int $id
     * @param Path $model
     * @return void
     */
    public function replace(int $id, $model): void
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
        )->execute([':id' => $id]);
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
