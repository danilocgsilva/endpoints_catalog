<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Repositories;

use Danilocgsilva\EndpointsCatalog\Models\Dns;
use PDO;
use Danilocgsilva\EndpointsCatalog\Repositories\Interfaces\BaseRepositoryInterface;

/**
 * @template-implements BaseRepositoryInterface<Dns>
 */
class DnsRepository extends AbstractRepository implements BaseRepositoryInterface
{
    public const MODEL = Dns::class;

    /**
     * @param Dns $model
     * @return void
     */
    public function save($model): void
    {
        $this->pdo->prepare(
            sprintf("INSERT INTO %s (dns) VALUES (:dns)", self::MODEL::TABLENAME)
        )->execute([
            ':dns' => $model->dns
        ]);
    }

    public function get(int $id): Dns
    {
        $preResults = $this->pdo->prepare(
            sprintf("SELECT dns, port FROM %s WHERE id = :id;", self::MODEL::TABLENAME)
        );
        $preResults->setFetchMode(PDO::FETCH_NUM);
        $preResults->execute([
            ':id' => $id
        ]);
        $fetchedData = $preResults->fetch();

        $fetchedDns = (new Dns())->setDns($fetchedData[0]);
        if ($fetchedData[1]) {
            $fetchedDns->setPort($fetchedData[1]);
        }

        return $fetchedDns;
    }

    public function replace(int $id, $model): void
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

    /**
     * @return array<Dns>
     */
    public function list(): array
    {
        $query = sprintf(
            "SELECT %s, %s FROM %s;",
            "dns",
            "port",
            self::MODEL::TABLENAME
        );

        $preResults = $this->pdo->prepare($query);
        $preResults->setFetchMode(PDO::FETCH_NUM);
        $preResults->execute();
        $dnsRepositoryList = [];
        while ($row = $preResults->fetch()) {
            $fetchedDns = (new Dns())->setDns($row[0]);
            if ($row[1]) {
                $fetchedDns->setPort($row[1]);
            }
            $dnsRepositoryList[] = $fetchedDns;
        }
        return $dnsRepositoryList; 
    }
}
