<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Repositories;

use Danilocgsilva\EndpointsCatalog\Pattern\TraitModel;
use Danilocgsilva\EndpointsCatalog\Models\Dns;
use PDO;

class DnsRepository extends AbstractRepository implements RepositoryInterface
{
    public const MODEL = Dns::class;

    public function save(TraitModel $model): void
    {
        $this->pdo->prepare(
            sprintf("INSERT INTO %s (dns) VALUES (:dns)", self::MODEL::TABLENAME)
        )->execute([
            ':dns' => $model->dns
        ]);
    }

    public function get(int $id): TraitModel
    {
        $preResults = $this->pdo->prepare(
            sprintf("SELECT dns FROM %s WHERE id = :id;", self::MODEL::TABLENAME)
        );
        $preResults->setFetchMode(PDO::FETCH_NUM);
        $preResults->execute([':id' => $id]);
        $fetchedData = $preResults->fetch();
        return (new Dns())
            ->setDns($fetchedData[0]);
    }

    public function replace(int $id, TraitModel $model): void
    {
        $query = sprintf(
            "UPDATE %s SET dns = :dns WHERE id = :id;",
            self::MODEL::TABLENAME
        );

        $this->pdo->prepare($query)->execute([
            ':dns' => $model->dns,
            ':id' => $id
        ]);
    }

    public function delete(int $id): void
    {
        $this->pdo->prepare(
            sprintf("DELETE FROM %s WHERE id = :id", self::MODEL::TABLENAME)
        )->execute([":id" => $id]);
    }

    public function list(): array
    {
        $query = sprintf(
            "SELECT id, %s FROM %s;",
            "dns_id",
            self::MODEL::TABLENAME
        );

        $preResults = $this->pdo->prepare($query);
        $preResults->setFetchMode(PDO::FETCH_NUM);
        $preResults->execute();
        $dnsRepositoryList = [];
        while ($row = $preResults->fetch()) {
            $dnsRepositoryList[] = (new Dns())->setDns($row[1]);
        }
        return $dnsRepositoryList; 
    }
}
