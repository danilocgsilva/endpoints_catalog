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
            sprintf("INSERT INTO %s (dns, `description`) VALUES (:dns, :description)", self::MODEL::TABLENAME)
        )->execute([
            ':dns' => $dnsModel->dns,
            ':description' => $dnsModel->description ?? ""
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
            sprintf("SELECT `id`, `dns`, `port`, `description` FROM %s WHERE id = :id;", self::MODEL::TABLENAME)
        );
        $preResults->setFetchMode(PDO::FETCH_ASSOC);
        $preResults->execute([
            ':id' => $id
        ]);
        $fetchedData = $preResults->fetch();
        extract($fetchedData);

        $fetchedDns = (new Dns())
            ->setId($id)
            ->setDns($dns);

        if ($description) {
            $fetchedDns->setDescription($description);
        }
        
        if ($port) {
            $fetchedDns->setPort($port);
        }

        return $fetchedDns;
    }

    public function replace(int $id, $model): void
    {
        $query = sprintf(
            "UPDATE %s SET dns = :dns, description = :description WHERE id = :id;",
            self::MODEL::TABLENAME
        );

        $this->pdo->prepare($query)->execute([
            ':dns' => $model->dns,
            ':id' => $id,
            ':description' => $model->description ?? null
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
            "SELECT `id`, `dns`, `port`, `description` FROM %s;",
            self::MODEL::TABLENAME
        );

        $preResults = $this->pdo->prepare($query);
        $preResults->setFetchMode(PDO::FETCH_ASSOC);
        $preResults->execute();
        $dnsRepositoryList = [];
        while ($row = $preResults->fetch()) {
            extract($row);
            $fetchedDns = (new Dns())->setId($id)->setDns($dns);
            if ($port) {
                $fetchedDns->setPort($port);
            }
            if ($description) {
                $fetchedDns->setDescription($description);
            }
            $dnsRepositoryList[] = $fetchedDns;
        }
        return $dnsRepositoryList; 
    }
}
