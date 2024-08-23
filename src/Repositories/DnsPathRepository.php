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
            sprintf("INSERT INTO %s (path_id, dns_id) VALUES (:path_id, :dns_id)", self::MODEL::TABLENAME)
        )->execute([
            ':path_id' => $model->path_id,
            ':dns_id' => $model->dns_id
        ]);
    }

    public function get(int $id): TraitModel
    {
        $preResults = $this->pdo->prepare(sprintf("SELECT path_id, dns_id FROM %s WHERE id = :id;", self::MODEL::TABLENAME));
        $preResults->setFetchMode(PDO::FETCH_NUM);
        $preResults->execute([':id' => $id]);
        $fetchedData = $preResults->fetch();
        return (new DnsPath())
            ->setPathId($fetchedData[0])
            ->setDnsId($fetchedData[1]);
    }

    public function replace(int $id, TraitModel $model): void
    {
        $query = sprintf(
            "UPDATE %s SET path_id = :path_id, dns_id = :dns_id WHERE id = :id;",
            self::MODEL::TABLENAME
        );

        $this->pdo->prepare($query)->execute([
            ':path_id' => $model->path_id,
            ':dns_id' => $model->dns_id,
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
            "SELECT id, %s, %s FROM %s;",
            "path_id",
            "dns_id",
            self::MODEL::TABLENAME
        );

        $preResults = $this->pdo->prepare($query);
        $preResults->setFetchMode(PDO::FETCH_NUM);
        $preResults->execute();
        $dnsPathRepositoryList = [];
        while ($row = $preResults->fetch()) {
            $dnsPathRepositoryList[] = (new DnsPath())->setPathId($row[1])->setPathId($row[2]);
        }
        return $dnsPathRepositoryList; 
    }
}
