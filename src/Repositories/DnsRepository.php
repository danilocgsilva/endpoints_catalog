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
     * @param Dns $dnsModel
     * @return void
     */
    public function save($dnsModel): void
    {
        $this->pdo->prepare(
            sprintf("INSERT INTO %s (dns) VALUES (:dns)", self::MODEL::TABLENAME)
        )->execute([
            ':dns' => $dnsModel->dns
        ]);
    }

    /**
     * @param Dns $dnsModel
     * @return void
     */
    public function saveAndAssingId($dnsModel): void
    {
        $this->save($dnsModel);
        $preResults = $this->pdo->prepare("SELECT LAST_INSERT_ID();");
        $preResults->execute();
        $dnsModel->setId(
            $preResults->fetch(PDO::FETCH_NUM)[0]
        );
    }

    public function get(int $id): Dns
    {
        $preResults = $this->pdo->prepare(
            sprintf("SELECT id, dns, port FROM %s WHERE id = :id;", self::MODEL::TABLENAME)
        );
        $preResults->setFetchMode(PDO::FETCH_NUM);
        $preResults->execute([
            ':id' => $id
        ]);
        $fetchedData = $preResults->fetch();

        $fetchedDns = (new Dns())->setId($fetchedData[0])->setDns($fetchedData[1]);
        if ($fetchedData[2]) {
            $fetchedDns->setPort($fetchedData[2]);
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
            "SELECT id, %s, %s FROM %s;",
            "dns",
            "port",
            self::MODEL::TABLENAME
        );

        $preResults = $this->pdo->prepare($query);
        $preResults->setFetchMode(PDO::FETCH_NUM);
        $preResults->execute();
        $dnsRepositoryList = [];
        while ($row = $preResults->fetch()) {
            $fetchedDns = (new Dns())->setId($row[0])->setDns($row[1]);
            if ($row[2]) {
                $fetchedDns->setPort($row[2]);
            }
            $dnsRepositoryList[] = $fetchedDns;
        }
        return $dnsRepositoryList; 
    }
}
