<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Repositories;

use Danilocgsilva\EndpointsCatalog\Models\DnsPath;
use Danilocgsilva\EndpointsCatalog\Pattern\TraitModel;
use PDO;

class DnsPathRepository extends AbstractRepository implements RepositoryInterface
{
    public const MODEL = DnsPath::class;
    
    public function save(TraitModel $model): void
    {
        $this->pdo->prepare(
            sprintf("INSERT INTO %s (path_id, dns_id) VALUES (:path_id, :dns_id)", $model->path_id, $model->dns_id)
        )->execute();
    }

    public function get(int $id): TraitModel
    {
        $preResults = $this->pdo->prepare(sprintf("SELECT path_id, dns_id FROM %s WHERE id = :id;", self::MODEL::TABLENAME));
        $preResults->setFetchMode(PDO::FETCH_NUM);
        $preResults->execute([':id' => $id]);

    }

    public function replace(int $id, TraitModel $model): void
    {

    }

    public function delete(int $id): void
    {

    }

    public function list(): array
    {

    }
}
